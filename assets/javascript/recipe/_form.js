const $ = require("jquery");
import '../../styles/recipe/_form.scss';

$(document).ready(function () {
    function bindIngredientDeletionToButton($ingredientForm) {
        $ingredientForm.find('.ingredient-deletion-button').on('click', function (e) {
            // remove the ingredient from the form
            $ingredientForm.remove();

            // update nutritional values
            updateRecipeNutritionalValues();
        });
    }

    function bindLinebreakToInput($ingredientForm) {
        $ingredientForm.find('.ingredient-quantity').on('keypress', function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();

                let $collectionHolderClass = $('.add_item_link').data('collectionHolderClass');
                let ingredientsList = $('.' + $collectionHolderClass);
                let currentIngredient = $(this).parents('.ingredient');
                if (currentIngredient.is(':last-child')) {
                    // focused input is the last one, add a new ingredient form
                    addFormToCollection($collectionHolderClass);

                    // switch cursor to the newly created ingredient
                    ingredientsList.find('.ingredient-quantity').last().focus();
                } else {
                    // focused input is in the middle of the list, switch cursor to the next ingredient
                    currentIngredient.next().find('.ingredient-quantity').focus();
                }
            }
        });
    }

    function bindNutritionalValueUpdateToInputChange($ingredientForm) {
        $ingredientForm.find('.ingredient-quantity').on('input', function (e) {
            updateRecipeNutritionalValues();
            updateIngredientNutritionalValues($(this).closest('.ingredient'));
        });
    }

    function bindNutritionalValueUpdateToSelectChange($ingredientForm) {
        $ingredientForm.find('.ingredient-select').on('change', function (e) {
            updateRecipeNutritionalValues();
            updateIngredientNutritionalValues($(this).closest('.ingredient'));
        });
    }

    function bindMeasureTypeUpdateToSelectChange($ingredientForm) {
        $ingredientForm.find('.ingredient-select').on('change', function (e) {
            updateIngredientMeasureType($(this).closest('.ingredient'));
        });
    }

    function bindNutritionalUpdateToMeasureTypeSelectChange($ingredientForm) {
        $ingredientForm.find('.ingredient-quantity-type').on('change', function (e) {
            updateRecipeNutritionalValues();
            updateIngredientNutritionalValues($(this).closest('.ingredient'));
        });
    }

    function addFormToCollection($collectionHolderClass) {
        // get container of all ingredient forms
        let $collectionHolder = $('.' + $collectionHolderClass);
        // get form data-prototype
        let prototype = $collectionHolder.data('prototype');
        // get the new index
        let index = $collectionHolder.data('index');

        let newForm = prototype;
        // replace '__name__label__' in the prototype's HTML to
        // instead be a number based on how many items we have
        newForm = newForm.replace(/__name__label__/g, index);

        // replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        newForm = newForm.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // display the new form in the page
        let $newIngredientForm = $('<div class="ingredient"></div>').append(newForm);
        $collectionHolder.append($newIngredientForm)

        // deselect default option on ingredient selector
        $newIngredientForm.find('select').val([]);
        $newIngredientForm.find('select option').prop('selected', false);

        // add a delete link to the new form
        bindIngredientDeletionToButton($newIngredientForm);

        // allow pressing enter to move in ingredients list
        bindLinebreakToInput($newIngredientForm);

        // watch for ingredient quantity change
        bindNutritionalValueUpdateToInputChange($newIngredientForm);

        // watch for ingredient select change
        bindNutritionalValueUpdateToSelectChange($newIngredientForm);
        bindMeasureTypeUpdateToSelectChange($newIngredientForm);

        // update ingredient measure type selector
        updateIngredientMeasureType($newIngredientForm);

        // watch for measure type select change
        bindNutritionalUpdateToMeasureTypeSelectChange($newIngredientForm);

        // activate Select2 on ingredient selectors
        toggleSelect2OnIngredientSelector();
    }

    function updateRecipeNutritionalValues() {
        let recipeData = calculateRecipeNutritionalValues();

        updateRecipeNutritionalValue('proteins', Math.round(recipeData.proteins));
        updateRecipeNutritionalValue('carbohydrates',  Math.round(recipeData.carbohydrates));
        updateRecipeNutritionalValue('fat',  Math.round(recipeData.fat));
        updateRecipeNutritionalValue('energy',  Math.round(recipeData.energy));
    }

    function updateRecipeNutritionalValue(nutritionalValueName, recipeNutritionalValue) {
        // Find the recipe nutritional value span
        let recipeDataSpan = $('.recipe-' + nutritionalValueName);

        // Update the recipe nutritional value on screen
        recipeDataSpan.text(recipeNutritionalValue);

        // If the user is connected, update user nutritional data comparison
        let percentageDataSpan = $('.percentage-' + nutritionalValueName);
        if (percentageDataSpan !== undefined) {
            // Get user nutritional value
            let userNutritionalValue = $('.user-' + nutritionalValueName).first().text();

            // Calculate new percentage difference
            let newPercentageDifference = calculatePercentageDifference(recipeNutritionalValue, userNutritionalValue);

            // Find the percentage icon
            let percentageIcon = percentageDataSpan.parent().find('i');

            // If the difference is positive
            if (recipeNutritionalValue >= parseInt(userNutritionalValue)) {
                // We update the icon to UP
                percentageIcon.addClass('ri-arrow-up-circle-fill');
                percentageIcon.removeClass('ri-arrow-down-circle-fill');

                // We add a '+' before the new percentage difference
                newPercentageDifference = '+' + newPercentageDifference;

                // We change the icon color for the proteins
                if (nutritionalValueName === 'proteins') {
                    percentageIcon.addClass('text-success');
                    percentageIcon.removeClass('text-danger');
                }
            } else {
                // We update the icon to DOWN
                percentageIcon.addClass('ri-arrow-down-circle-fill');
                percentageIcon.removeClass('ri-arrow-up-circle-fill');

                // We change the icon color for the proteins
                if (nutritionalValueName === 'proteins') {
                    percentageIcon.addClass('text-danger');
                    percentageIcon.removeClass('text-success');
                }
            }

            // Update the percentage difference on screen
            percentageDataSpan.text(newPercentageDifference);
        }
    }

    function updateIngredientNutritionalValues(ingredient) {
        let selectedIngredient = $(ingredient).find('option:selected');
        let ingredientQuantity = $(ingredient).find('.ingredient-quantity').val();
        let measureType = $(ingredient).find('.ingredient-quantity-type option:selected');

        let proteins = parseFloat(selectedIngredient.data('proteins')),
            carbohydrates = parseFloat(selectedIngredient.data('carbohydrates')),
            fat = parseFloat(selectedIngredient.data('fat')),
            energy = parseFloat(selectedIngredient.data('energy')),
            proteinsQuantity = 0,
            carbohydratesQuantity = 0,
            fatQuantity = 0,
            energyQuantity = 0;

        if (measureType.hasClass('absolute-unit')) {
            // ingredient is currently measured by unit
            let conversionRate = parseFloat(selectedIngredient.data('unit-measure-conversion-rate'));

            proteinsQuantity += (proteins * conversionRate) * ingredientQuantity;
            carbohydratesQuantity += (carbohydrates * conversionRate) * ingredientQuantity;
            fatQuantity += (fat * conversionRate) * ingredientQuantity;
            energyQuantity += (energy * conversionRate) * ingredientQuantity;
        } else {
            // ingredient is measured by g, ml, ...
            proteinsQuantity += (proteins / 100) * ingredientQuantity;
            carbohydratesQuantity += (carbohydrates / 100) * ingredientQuantity;
            fatQuantity += (fat / 100) * ingredientQuantity;
            energyQuantity += (energy / 100) * ingredientQuantity;
        }

        updateIngredientNutritionalValue(ingredient, 'proteins', Math.round(proteinsQuantity) + ' g');
        updateIngredientNutritionalValue(ingredient, 'carbohydrates', Math.round(carbohydratesQuantity) + ' g');
        updateIngredientNutritionalValue(ingredient, 'fat', Math.round(fatQuantity) + ' g');
        updateIngredientNutritionalValue(ingredient, 'energy', Math.round(energyQuantity) + ' kcal');
    }

    function updateIngredientNutritionalValue(ingredient, nutritionalValueName, ingredientNutritionalValue) {
        // Find the ingredient nutritional value span
        let ingredientDataSpan = $(ingredient).find('#ingredient-' + nutritionalValueName);

        // Update the ingredient nutritional value on screen
        ingredientDataSpan.text(ingredientNutritionalValue);
    }

    function calculateRecipeNutritionalValues() {
        let recipeData = {
            'proteins': 0,
            'carbohydrates': 0,
            'fat': 0,
            'energy': 0,
        };

        $('.ingredient').each(function () {
            let selectedIngredient = $(this).find('.ingredient-select option:selected');
            let ingredientQuantity = $(this).find('.ingredient-quantity').val();
            let measureType = $(this).find('.ingredient-quantity-type option:selected');

            let proteins = parseFloat(selectedIngredient.data('proteins')),
                carbohydrates = parseFloat(selectedIngredient.data('carbohydrates')),
                fat = parseFloat(selectedIngredient.data('fat')),
                energy = parseFloat(selectedIngredient.data('energy'));

            if (measureType.hasClass('absolute-unit')) {
                // ingredient is currently measured by unit
                let conversionRate = parseFloat(selectedIngredient.data('unit-measure-conversion-rate'));

                recipeData['proteins'] += (proteins * conversionRate) * ingredientQuantity;
                recipeData['carbohydrates'] += (carbohydrates * conversionRate) * ingredientQuantity;
                recipeData['fat'] += (fat * conversionRate) * ingredientQuantity;
                recipeData['energy'] += (energy * conversionRate) * ingredientQuantity;
            } else {
                // ingredient is measured by g, ml, ...
                recipeData['proteins'] += (proteins / 100) * ingredientQuantity;
                recipeData['carbohydrates'] += (carbohydrates / 100) * ingredientQuantity;
                recipeData['fat'] += (fat / 100) * ingredientQuantity;
                recipeData['energy'] += (energy / 100) * ingredientQuantity;
            }
        });

        return recipeData;
    }

    function calculatePercentageDifference(recipeData, userData) {
        return Math.round(((recipeData - userData) / userData) * 100);
    }

    function updateIngredientMeasureType(ingredient) {
        let selectedIngredient = $(ingredient).find('.ingredient-select option:selected');
        let ingredientMeasureTypeSelect = $(ingredient).find('.ingredient-quantity-type');
        let ingredientMeasureTypeOption = ingredientMeasureTypeSelect.find('.ingredient-measure-type');

        ingredientMeasureTypeOption.html(selectedIngredient.data('measure-type'));

        if (!selectedIngredient.data('has-unit-measure-saved')) {
            ingredientMeasureTypeSelect.find('option').each(function() {
                if ($(this).hasClass('ingredient-measure-type')) {
                    $(this).prop('disabled', false);
                } else {
                    $(this).prop('disabled', true);
                }
            })
        } else {
            ingredientMeasureTypeSelect.find('option').each(function() {
                $(this).prop('disabled', false);
            })
        }
    }

    function toggleSelect2OnIngredientSelector() {
        $('.ingredient-select').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
        });
    }

    // Update the recipe nutritional values on page start
    updateRecipeNutritionalValues();
    $('.ingredient').each(function () {
        updateIngredientNutritionalValues($(this));
        updateIngredientMeasureType($(this));
    });

    // activate Select2 on ingredient selectors
    toggleSelect2OnIngredientSelector();

    // get the element that holds the collection of ingredients
    let $collectionHolder = $('#ingredients');

    // bind actions to the different elements
    $collectionHolder.children('div').each(function () {
        bindIngredientDeletionToButton($(this));
        bindLinebreakToInput($(this));
        bindNutritionalValueUpdateToInputChange($(this));
        bindNutritionalValueUpdateToSelectChange($(this));
        bindMeasureTypeUpdateToSelectChange($(this));
        bindNutritionalUpdateToMeasureTypeSelectChange($(this));
    });

    // count the current ingredients we have and
    // use this as new index when inserting new items
    $collectionHolder.data('index', $collectionHolder.find('.ingredient').length);

    $('body').on('click', '.add_item_link', function (e) {
        let $collectionHolderClass = $(e.currentTarget).data('collectionHolderClass');

        // add a new ingredient form
        addFormToCollection($collectionHolderClass);
    });
});
