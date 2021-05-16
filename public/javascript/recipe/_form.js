jQuery(document).ready(function () {
    function bindIngredientDeletionToButton($ingredientForm) {
        $ingredientForm.find('.ingredient-deletion-button').on('click', function (e) {
            // remove the ingredient from the form
            $ingredientForm.remove();

            // update nutritional values
            updateNutritionalValues();
        });
    }

    function bindNutritionalValueUpdateToInputChange($ingredientForm) {
        $ingredientForm.find('.ingredient-quantity').on('input', function (e) {
            updateNutritionalValues();
        });
    }

    function bindNutritionalValueUpdateToSelectChange($ingredientForm) {
        $ingredientForm.find('.ingredient-select').on('change', function (e) {
            updateNutritionalValues();
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
    }

    function updateNutritionalValues() {
        let recipeData = calculateRecipeNutritionalValues();

        updateNutritionalValue('proteins', Math.round(recipeData.proteins));
        updateNutritionalValue('carbohydrates',  Math.round(recipeData.carbohydrates));
        updateNutritionalValue('fat',  Math.round(recipeData.fat));
        updateNutritionalValue('energy',  Math.round(recipeData.energy));
    }

    function updateNutritionalValue(nutritionalValueName, recipeNutritionalValue) {
        // Find the recipe nutritional value span
        let recipeDataSpan = $('#recipe-' + nutritionalValueName);

        // Update the recipe nutritional value on screen
        recipeDataSpan.text(recipeNutritionalValue);

        let percentageDataSpan = $('#percentage-' + nutritionalValueName);

        // If the user is connected
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

        console.debug(recipeData);

        return recipeData;
    }

    function calculatePercentageDifference(recipeData, userData) {
        let percentageDifference = Math.round(((recipeData - userData) / userData) * 100);

        return percentageDifference;
    }

    // Update the recipe nutritional values on page start
    updateNutritionalValues();

    // get the element that holds the collection of ingredients
    var $collectionHolder = $('#ingredients');

    // bind deletion button to existing ingredient forms
    // bind quantity change to existing ingredient forms
    // bind select change to existing ingredient forms
    $collectionHolder.children('div').each(function () {
        bindIngredientDeletionToButton($(this));
        bindNutritionalValueUpdateToInputChange($(this));
        bindNutritionalValueUpdateToSelectChange($(this));
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
