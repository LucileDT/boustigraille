jQuery(document).ready(function () {
    function bindIngredientDeletionToButton($ingredientForm) {
        $ingredientForm.find('.ingredient-deletion-button').on('click', function (e) {
            // remove the ingredient from the form
            $ingredientForm.remove();

            // update nutritional values
            updateRecipeNutritionalValues();
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

    function addFormToCollection($collectionHolderClass) {
        // get container of all ingredient forms
        var $collectionHolder = $('.' + $collectionHolderClass);
        // get form data-prototype
        var prototype = $collectionHolder.data('prototype');
        // get the new index
        var index = $collectionHolder.data('index');

        var newForm = prototype;
        // replace '__name__label__' in the prototype's HTML to
        // instead be a number based on how many items we have
        newForm = newForm.replace(/__name__label__/g, index);

        // replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        newForm = newForm.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // display the new form in the page
        var $newIngredientForm = $('<div class="ingredient"></div>').append(newForm);
        $collectionHolder.append($newIngredientForm)

        // add a delete link to the new form
        bindIngredientDeletionToButton($newIngredientForm);

        // watch for ingredient quantity change
        bindNutritionalValueUpdateToInputChange($newIngredientForm);

        // watch for ingredient select change
        bindNutritionalValueUpdateToSelectChange($newIngredientForm);
        bindMeasureTypeUpdateToSelectChange($newIngredientForm);

        // update ingredient measure type selector
        updateIngredientMeasureType($newIngredientForm);
    }

    function updateRecipeNutritionalValues() {
        let recipeData = calculateRecipeNutritionalValues();

        updateRecipeNutritionalValue('proteins', Math.round(recipeData.proteins));
        updateRecipeNutritionalValue('carbohydrates',  Math.round(recipeData.carbohydrates));
        updateRecipeNutritionalValue('fat',  Math.round(recipeData.fat));
        updateRecipeNutritionalValue('energy',  Math.round(recipeData.energy));
    }

    function updateRecipeNutritionalValue(nutritionalValueName, recipeNutritionalValue) {
        // Find the recipe nutritional value span
        let recipeDataSpan = $('#recipe-' + nutritionalValueName);

        // Update the recipe nutritional value on screen
        recipeDataSpan.text(recipeNutritionalValue);

        // If the user is connected, update user nutritional data comparison
        let percentageDataSpan = $('#percentage-' + nutritionalValueName);
        if (percentageDataSpan != undefined) {
            // Get user nutritional value
            let userNutritionalValue = $('#user-' + nutritionalValueName).text();

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

    function updateIngredientNutritionalValues(ingredient) {
        let selectedIngredient = $(ingredient).find('option:selected');
        let ingredientQuantity = $(ingredient).find('.ingredient-quantity').val();

        updateIngredientNutritionalValue(ingredient, 'proteins', Math.round(selectedIngredient.data('proteins') / 100 * ingredientQuantity) + ' g');
        updateIngredientNutritionalValue(ingredient, 'carbohydrates', Math.round(selectedIngredient.data('carbohydrates') / 100 * ingredientQuantity) + ' g');
        updateIngredientNutritionalValue(ingredient, 'fat', Math.round(selectedIngredient.data('fat') / 100 * ingredientQuantity) + ' g');
        updateIngredientNutritionalValue(ingredient, 'energy', Math.round(selectedIngredient.data('energy') / 100 * ingredientQuantity) + ' kcal');
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
            let selectedIngredient = $(this).find('option:selected');
            let ingredientQuantity = $(this).find('.ingredient-quantity').val();

            recipeData['proteins'] += (parseFloat(selectedIngredient.data('proteins')) / 100) * ingredientQuantity;
            recipeData['carbohydrates'] += (parseFloat(selectedIngredient.data('carbohydrates')) / 100) * ingredientQuantity;
            recipeData['fat'] += (parseFloat(selectedIngredient.data('fat')) / 100) * ingredientQuantity;
            recipeData['energy'] += (parseFloat(selectedIngredient.data('energy')) / 100) * ingredientQuantity;
        });

        return recipeData;
    }

    function calculatePercentageDifference(recipeData, userData) {
        let percentageDifference = Math.round(((recipeData - userData) / userData) * 100);

        return percentageDifference;
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

    // Update the recipe nutritional values on page start
    updateRecipeNutritionalValues();
    $('.ingredient').each(function () {
        updateIngredientNutritionalValues($(this));
        updateIngredientMeasureType($(this));
    });

    // get the element that holds the collection of ingredients
    var $collectionHolder = $('#ingredients');

    // bind deletion button to existing ingredient forms
    // bind quantity change to existing ingredient forms
    // bind select change to existing ingredient forms
    $collectionHolder.children('div').each(function () {
        bindIngredientDeletionToButton($(this));
        bindNutritionalValueUpdateToInputChange($(this));
        bindNutritionalValueUpdateToSelectChange($(this));
        bindMeasureTypeUpdateToSelectChange($(this));
    });

    // count the current ingredients we have and
    // use this as new index when inserting new items
    $collectionHolder.data('index', $collectionHolder.find('.ingredient').length);

    $('body').on('click', '.add_item_link', function (e) {
        var $collectionHolderClass = $(e.currentTarget).data('collectionHolderClass');

        // add a new ingredient form
        addFormToCollection($collectionHolderClass);
    })
});
