jQuery(document).ready(function () {
    function bindMealDeletionToButton($mealListForm) {
        $mealListForm.find('.meal-deletion-button').on('click', function (e) {
            // remove the meal from the form
            $mealListForm.remove();
        });
    }

    function bindLinebreakToInput($mealListForm) {
        $mealListForm.find('.meal-quantity').on('keypress', function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();

                let $collectionHolderClass = $('.add_item_link').data('collectionHolderClass');
                let mealsList = $('.' + $collectionHolderClass);
                let currentMeal = $(this).parents('.meal');
                if (currentMeal.is(':last-child')) {
                    // focused input is the last one, add a new meal form
                    addFormToCollection($collectionHolderClass);

                    // switch cursor to the newly created meal
                    mealsList.find('.meal-quantity').last().focus();
                }
                else
                {
                    // focused input is in the middle of the list, switch cursor to the next meal
                    currentMeal.next().find('.meal-quantity').focus();
                }
            }
        });
    }

    function bindBringFocusToQuantity($mealListForm) {
        $mealListForm.find('.meal-select').on('change', function (e) {
            // defined in global.js
            focusNextElement();
        });
    }

    function toggleSelect2OnIngredientSelector() {
        $('.meal-select').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
        });
    }

    function addFormToCollection($collectionHolderClass) {
        // get container of all meal forms
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
        var $newMealListForm = $('<div class="meal"></div>').append(newForm);
        $collectionHolder.append($newMealListForm)

        // add a delete link to the new form
        bindMealDeletionToButton($newMealListForm);

        // allow pressing enter to move in meals list
        bindLinebreakToInput($newMealListForm);

        // bring focus to the quantity field when selection a meal
        bindBringFocusToQuantity($newMealListForm);

        // activate Select2 on ingredient selectors
        toggleSelect2OnIngredientSelector();
    }

    // get the element that holds the collection of meals
    var $collectionHolder = $('#meals');

    // bind actions to the different elements
    $collectionHolder.children('div').each(function () {
        bindMealDeletionToButton($(this));
        bindLinebreakToInput($(this));
        bindBringFocusToQuantity($(this));
    });

    // count the current meals we have and
    // use this as new index when inserting new items
    $collectionHolder.data('index', $collectionHolder.find('.meal').length);

    // activate Select2 on ingredient selectors
    toggleSelect2OnIngredientSelector();

    $('body').on('click', '.add_item_link', function (e) {
        var $collectionHolderClass = $(e.currentTarget).data('collectionHolderClass');

        // add a new meal form
        addFormToCollection($collectionHolderClass);
    });

    $('#meal_list_startDate').on('change', function (e) {
        // get start date value as string
        let startDateString = $(this).val();

        // make it a javascript date object
        let startDate = new Date(startDateString);

        // create a new date
        let startDateMoreOneWeek = new Date();

        // set its value to startDate + 1 week (7 days)
        startDateMoreOneWeek.setDate(startDate.getDate() + 7);

        // update the endDate value
        $('#meal_list_endDate').val(startDateMoreOneWeek.toISOString().substring(0, 10));
    });
});
