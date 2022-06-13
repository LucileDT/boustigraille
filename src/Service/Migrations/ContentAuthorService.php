<?php

namespace App\Service\Migrations;

use Doctrine\ORM\EntityManagerInterface;

class ContentAuthorService
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Using Recipe title, link an author to it
     * @throws \Doctrine\DBAL\Exception
     */
    public function updateRecipesAuthor()
    {
        $connection = $this->entityManager->getConnection();
        $stmt = $connection->prepare('SELECT * FROM recipe');
        $result = $stmt->executeQuery();
        $recipes = $result->fetchAllAssociative();
        foreach ($recipes as $recipe) {
            if (empty($recipe['author_id'])) {
                // search usernames in recipe title
                $namesIndex = strpos($recipe['name'], '(');
                $userId = null;
                if ($namesIndex > 0) {
                    $namesLength = strpos($recipe['name'], ')', $namesIndex) - $namesIndex - 1;
                    $names = explode(',', substr($recipe['name'], $namesIndex + 1, $namesLength));
                    foreach ($names as $name) {
                        if (empty($userId)) {
                            $stmt = $connection->prepare("SELECT * FROM \"user\" WHERE LOWER(\"username\") LIKE ?");
                            $stmt->bindValue(1, '%' . strtolower(trim($name)) . '%');
                            $result = $stmt->executeQuery();
                            $users = $result->fetchAllAssociative();
                            if(!empty($users)) {
                                $userId = $users[0]['id'];
                            }
                        }
                    }
                }
                // if no username has been found, use the older one
                if (empty($userId)) {
                    $stmt = $connection->prepare("SELECT * FROM \"user\" ORDER BY id");
                    $result = $stmt->executeQuery();
                    $users = $result->fetchAllAssociative();
                    if(!empty($users)) {
                        $userId = $users[0]['id'];
                    }
                }
                $stmt = $connection->prepare("UPDATE recipe SET author_id = ? WHERE id = ?");
                $stmt->bindValue(1, $userId);
                $stmt->bindValue(2, $recipe['id']);
                $result = $stmt->executeQuery();
            }
        }
    }

    /**
     * Using Meal List title, link an author to it
     * @throws \Doctrine\DBAL\Exception
     */
    public function updateMealListsAuthor()
    {
        $connection = $this->entityManager->getConnection();
        $stmt = $connection->prepare('SELECT * FROM meal_list');
        $result = $stmt->executeQuery();
        $mealLists = $result->fetchAllAssociative();
        foreach ($mealLists as $mealList) {
            if (empty($mealList['author_id'])) {
                // search usernames in meal list person name
                $userId = null;
                $names = explode(',', $mealList['person_name']);
                foreach ($names as $name) {
                    if (empty($userId)) {
                        $stmt = $connection->prepare("SELECT * FROM \"user\" WHERE LOWER(\"username\") LIKE ?");
                        $stmt->bindValue(1, '%' . strtolower(trim($name)) . '%');
                        $result = $stmt->executeQuery();
                        $users = $result->fetchAllAssociative();
                        if(!empty($users)) {
                            $userId = $users[0]['id'];
                        }
                    }
                }
                // if no username has been found, use the older one
                if (empty($userId)) {
                    $stmt = $connection->prepare("SELECT * FROM \"user\" ORDER BY id");
                    $result = $stmt->executeQuery();
                    $users = $result->fetchAllAssociative();
                    if(!empty($users)) {
                        $userId = $users[0]['id'];
                    }
                }
                $stmt = $connection->prepare("UPDATE meal_list SET author_id = ? WHERE id = ?");
                $stmt->bindValue(1, $userId);
                $stmt->bindValue(2, $mealList['id']);
                $result = $stmt->executeQuery();
            }
        }
    }
}
