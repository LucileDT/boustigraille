<?php

namespace App\DataFixtures;

use App\Entity\FollowProposition;
use App\Entity\FollowType;
use App\Entity\User;
use App\Service\NotificationService;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FollowPropositionFixtures extends Fixture implements DependentFixtureInterface
{
    public const EASY_TAG_REFERENCE = 'easy-tag';

    public function __construct(
        private UrlGeneratorInterface $router,
        private NotificationService $notificationService,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();
        $followPropositionUrl = $this->router->generate('my_account_follow_propositions');

        // admin follows all of sylvain content
        $followProposition1 = new FollowProposition();
        $followProposition1->setFollower($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
        $followProposition1->setFollowed($this->getReference(UserFixtures::BASIL_USER_REFERENCE));
        $followProposition1->setType($this->getReference(FollowTypeFixtures::USERNAME_ON_RECIPE_FOLLOW_TYPE_REFERENCE));
        $followProposition1->setProposedAt($now->sub(new DateInterval('P3DT3H')));
        $followProposition1->setAcceptedAt($now->sub(new DateInterval('P2DT1H')));
        $manager->persist($followProposition1);
        $this->generateNotification(
            $this->getReference(FollowTypeFixtures::USERNAME_ON_RECIPE_FOLLOW_TYPE_REFERENCE),
            $this->getReference(UserFixtures::BASIL_USER_REFERENCE),
            $this->getReference(UserFixtures::ADMIN_USER_REFERENCE),
            true
        );

        $followProposition2 = new FollowProposition();
        $followProposition2->setFollower($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
        $followProposition2->setFollowed($this->getReference(UserFixtures::BASIL_USER_REFERENCE));
        $followProposition2->setType($this->getReference(FollowTypeFixtures::MEAL_LIST_FOLLOW_TYPE_REFERENCE));
        $followProposition2->setProposedAt($now->sub(new DateInterval('P2DT3H')));
        $followProposition2->setAcceptedAt($now->sub(new DateInterval('P1DT6H')));
        $manager->persist($followProposition2);
        $this->generateNotification(
            $this->getReference(FollowTypeFixtures::MEAL_LIST_FOLLOW_TYPE_REFERENCE),
            $this->getReference(UserFixtures::BASIL_USER_REFERENCE),
            $this->getReference(UserFixtures::ADMIN_USER_REFERENCE),
            true
        );

        // brunehilde and basil follow their usernames on recipes
        $followProposition3 = new FollowProposition();
        $followProposition3->setFollower($this->getReference(UserFixtures::BRUNEHILDE_USER_REFERENCE));
        $followProposition3->setFollowed($this->getReference(UserFixtures::BASIL_USER_REFERENCE));
        $followProposition3->setType($this->getReference(FollowTypeFixtures::USERNAME_ON_RECIPE_FOLLOW_TYPE_REFERENCE));
        $followProposition3->setProposedAt($now->sub(new DateInterval('P3DT3H')));
        $followProposition3->setAcceptedAt($now);
        $manager->persist($followProposition3);
        $this->generateNotification(
            $this->getReference(FollowTypeFixtures::MEAL_LIST_FOLLOW_TYPE_REFERENCE),
            $this->getReference(UserFixtures::BASIL_USER_REFERENCE),
            $this->getReference(UserFixtures::BRUNEHILDE_USER_REFERENCE),
            true
        );

        $followProposition4 = new FollowProposition();
        $followProposition4->setFollower($this->getReference(UserFixtures::BASIL_USER_REFERENCE));
        $followProposition4->setFollowed($this->getReference(UserFixtures::BRUNEHILDE_USER_REFERENCE));
        $followProposition4->setType($this->getReference(FollowTypeFixtures::USERNAME_ON_RECIPE_FOLLOW_TYPE_REFERENCE));
        $followProposition4->setProposedAt($now->sub(new DateInterval('P3DT3H')));
        $followProposition4->setAcceptedAt($now);
        $manager->persist($followProposition4);
        $this->generateNotification(
            $this->getReference(FollowTypeFixtures::MEAL_LIST_FOLLOW_TYPE_REFERENCE),
            $this->getReference(UserFixtures::BRUNEHILDE_USER_REFERENCE),
            $this->getReference(UserFixtures::BASIL_USER_REFERENCE),
            true
        );

        // admin has follow requests waiting for approval
        $followProposition5 = new FollowProposition();
        $followProposition5->setFollower($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
        $followProposition5->setFollowed($this->getReference(UserFixtures::SYLVAIN_USER_REFERENCE));
        $followProposition5->setType($this->getReference(FollowTypeFixtures::MEAL_LIST_FOLLOW_TYPE_REFERENCE));
        $followProposition5->setProposedAt($now->sub(new DateInterval('PT3H')));
        $manager->persist($followProposition5);
        $this->generateNotification(
            $this->getReference(FollowTypeFixtures::MEAL_LIST_FOLLOW_TYPE_REFERENCE),
            $this->getReference(UserFixtures::SYLVAIN_USER_REFERENCE),
            $this->getReference(UserFixtures::ADMIN_USER_REFERENCE),
        );

        $followProposition6 = new FollowProposition();
        $followProposition6->setFollower($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
        $followProposition6->setFollowed($this->getReference(UserFixtures::SERAPHINA_USER_REFERENCE));
        $followProposition6->setType($this->getReference(FollowTypeFixtures::USERNAME_ON_RECIPE_FOLLOW_TYPE_REFERENCE));
        $followProposition6->setProposedAt($now->sub(new DateInterval('PT1H')));
        $manager->persist($followProposition6);
        $this->generateNotification(
            $this->getReference(FollowTypeFixtures::USERNAME_ON_RECIPE_FOLLOW_TYPE_REFERENCE),
            $this->getReference(UserFixtures::SERAPHINA_USER_REFERENCE),
            $this->getReference(UserFixtures::ADMIN_USER_REFERENCE),
        );

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    /**
     * Generate a notification for a FollowProposition
     *
     * @param FollowType $followType
     * @param User $followed
     * @param User $follower
     * @param bool $isRead True if the notification should be marked as read, false by default
     * @return void
     */
    private function generateNotification(
        FollowType $followType,
        User $followed,
        User $follower,
        bool $isRead = false
    ): void
    {
        $followPropositionUrl = $this->router->generate('my_account_follow_propositions');

        $notification = new Notification(
            $followType->getLabel(),
            ['browser']
        );

        if ($followType->getCode() === FollowType::USERNAME_ON_RECIPE) {
            $notification->content(
                '**' . $followed->getUsername() . '**' .
                ' vous propose de voir son **pseudo** dans les **recettes** qu\'ael a rédigées.<br>' .
                'Vous pouvez voir sa proposition sur la page des [Demandes de suivi](' . $followPropositionUrl . ') !'
            );
        } else {
            $notification->content(
                '**' . $followed->getUsername() . '**' .
                ' vous propose l\'accès à ses **listes de repas**.<br>' .
                'Vous pouvez voir sa proposition sur la page des [Demandes de suivi](' . $followPropositionUrl . ') !'
            );
        }

        $notificationHistory = $this->notificationService->sendNotification(
            $notification,
            $follower,
            $followed
        );
        $notificationReceived = $notificationHistory->getNotificationReceived();
        if ($isRead) {
            $now = new DateTimeImmutable();
            $notificationReceived->setReadAt($now);
        }
    }
}
