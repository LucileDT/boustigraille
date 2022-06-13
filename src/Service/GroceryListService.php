<?php

namespace App\Service;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class containing methods used to manage grocery lists
 */
class GroceryListService
{
    /**
     * Return a formatted grocery list generated from meal lists.
     *
     * @param ArrayCollection $mealLists
     *
     * @return array Formatted grocery list
     */
    public static function generateFormattedGroceryList(ArrayCollection $mealLists)
    {
        $groceryList = [];

        foreach ($mealLists as $mealList) {
            $meals = $mealList->getMeals();

            foreach ($meals as $mealQuantityForList) {
                $ingredients = $mealQuantityForList->getMeal()->getIngredients();
                $mealQuantity = $mealQuantityForList->getQuantity();

                foreach ($ingredients as $ingredientQuantityForRecipe) {
                    // ingredient data
                    $ingredient = $ingredientQuantityForRecipe->getIngredient();
                    $ingredientId = $ingredient->getId();
                    $ingredientQuantity = $ingredientQuantityForRecipe->getQuantity();
                    $isMeasuredByUnit = $ingredientQuantityForRecipe->isMeasuredByUnit();
                    $unitSize = $ingredient->getUnitSize();

                    if ($isMeasuredByUnit) {
                        $quantity = $mealQuantity * $ingredientQuantity * $unitSize;
                        $unitQuantity = $mealQuantity * $ingredientQuantity;
                    } else {
                        $quantity = $mealQuantity * $ingredientQuantity;
                        $unitQuantity = null;
                    }

                    if (!array_key_exists($ingredientId, $groceryList)) {
                        $groceryList[$ingredientId] = [
                            'id' => $ingredientId,
                            'label' => $ingredient->getLabel(),
                            'quantity' => $quantity,
                            'unitQuantity' => $unitQuantity,
                            'unitSize' => $unitSize,
                            'measureType' => $ingredient->getMeasureType(),
                            'isMeasuredByUnit' => $isMeasuredByUnit,
                        ];
                    } else {
                        $groceryList[$ingredientId]['quantity'] += $quantity;
                        $groceryList[$ingredientId]['unitQuantity'] += $unitQuantity;
                    }
                }
            }
        }

        return $groceryList;
    }
}
