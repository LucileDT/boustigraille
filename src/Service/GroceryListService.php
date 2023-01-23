<?php

namespace App\Service;

use App\Entity\MealList;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class containing methods used to manage grocery lists
 */
class GroceryListService
{
    /**
     * Return a formatted grocery list generated from meal lists.
     *
     * @param ArrayCollection|MealList[] $mealLists
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

                    $storeId = empty($ingredient->getStore()) ? 0 : $ingredient->getStore()->getSortNumber();

                    if (!array_key_exists($storeId, $groceryList)) {
                        $groceryList[$storeId] = [];
                    }
                    if (!array_key_exists($ingredientId, $groceryList[$storeId])) {
                        $groceryList[$storeId][$ingredientId] = [
                            'id' => $ingredientId,
                            'label' => $ingredient->getLabel(),
                            'quantity' => $quantity,
                            'unitQuantity' => $unitQuantity,
                            'unitSize' => $unitSize,
                            'measureType' => $ingredient->getMeasureType(),
                            'isMeasuredByUnit' => $isMeasuredByUnit,
                            'store' => [
                                'id' => empty($ingredient->getStore()) ? null : $ingredient->getStore()->getId(),
                                'label' => empty($ingredient->getStore()) ? null : $ingredient->getStore()->getLabel(),
                            ],
                        ];
                    } else {
                        $groceryList[$storeId][$ingredientId]['quantity'] += $quantity;
                        $groceryList[$storeId][$ingredientId]['unitQuantity'] += $unitQuantity;
                    }
                }
            }
        }

        return $groceryList;
    }
}
