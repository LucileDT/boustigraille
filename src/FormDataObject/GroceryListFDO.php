<?php

namespace App\FormDataObject;

use App\Entity\MealList;
use Doctrine\Common\Collections\ArrayCollection;

class GroceryListFDO
{
    /**
     * @var ArrayCollection
     */
    private $mealLists;

    public function __construct()
    {
        $this->mealLists = new ArrayCollection();
    }

    /**
     * Get the value of mealLists
     *
     * @return ArrayCollection
     */
    public function getMealLists()
    {
        return $this->mealLists;
    }

    /**
     * Set the value of mealLists
     *
     * @param ArrayCollection $mealLists
     *
     * @return self
     */
    public function setMealLists(ArrayCollection $mealLists)
    {
        $this->mealLists = $mealLists;

        return $this;
    }

    /**
     * Add a mealList to the GroceryListFDO
     *
     * @param MealList $mealList The mealList to add.
     */
    public function addMealList(MealList $mealList)
    {
        if (!$this->hasMealList($mealList))
        {
            $this->mealLists[] = $mealList;
        }
    }

    /**
     * Remove a mealList from the GroceryListFDO
     *
     * @param MealList $mealListToRemove The mealList to remove.
     */
    function removeMealList(MealList $mealListToRemove)
    {
        $ownedMealLists = $this->getMealLists();
        foreach ($ownedMealLists as $index => $ownedMealList)
        {
            if ($ownedMealList->getId() === $mealListToRemove->getId())
            {
                unset($ownedMealLists[$index]);
            }
        }
    }

    /**
     * Check if this member is already selected
     *
     * @param MealList $mealList The mealList to add.
     *
     * @return boolean
     */
    function hasMealList(MealList $mealList)
    {
        if (count($this->mealLists) > 0)
        {
            foreach ($this->mealLists as $ownedMealList)
            {
                if ($ownedMealList->getId() === $mealList->getId())
                {
                    return true;
                }
            }
        }

        return false;
    }
}
