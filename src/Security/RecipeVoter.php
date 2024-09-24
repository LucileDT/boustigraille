<?php

namespace App\Security;

use App\Entity\FollowType;
use App\Entity\Post;
use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\FollowPropositionRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RecipeVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW_AUTHOR_USERNAME = 'view_author_username';

    public function __construct(
        private FollowPropositionRepository $followPropositionRepository
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW_AUTHOR_USERNAME])) {
            return false;
        }

        // only vote on `Recipe` objects
        if (!$subject instanceof Recipe) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // you know $subject is a Recipe object, thanks to `supports()`
        /** @var Recipe $recipe */
        $recipe = $subject;

        return match($attribute) {
            self::VIEW_AUTHOR_USERNAME => $this->canViewAuthorUsername($recipe, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canViewAuthorUsername(Recipe $recipe, ?User $user): bool
    {
        $author = $recipe->getAuthor();

        if ($author->doShowUsernameOnRecipe()) {
            return true;
        }

        // Only continue if the user is connected
        if (is_null($user)) {
            return false;
        }

        if ($author === $user) {
            return true;
        }

        return $this->followPropositionRepository->hasAcceptedFollowPropositionFromUser(
            $user, $author, FollowType::USERNAME_ON_RECIPE
        );
    }
}