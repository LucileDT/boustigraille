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
        $mealListForm.find('.meal-select').on('select2:close', function (e) {
            // get quantity input and focus on it
            $(this).parents('.meal').find('.meal-quantity').focus();
        });
    }

    function bindBringFocusToSelect2Input($mealListForm) {
        $mealListForm.find('.meal-select').on('select2:open', function (e) {
            document.querySelector(".select2-container--open .select2-search__field").focus();
        });
    }

    function bindCountPlannedMealToInput($newMealListForm) {
        $newMealListForm.find('.meal-quantity').on('input', function () {
            countPlannedMeals();
        });
    }

    function toggleSelect2OnIngredientSelector() {
        let recipesUrl = $('#recipes-list-data').data('url');
        $('.meal-select').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            ajax: {
                url: recipesUrl,
                dataType: 'json',
                processResults: function (data) {
                    let favedRecipesData = data.faved;
                    let favedRecipes = [];
                    $.each(favedRecipesData, function (key, recipeData) {
                        let recipe = {
                            'id': recipeData.id,
                            'text': recipeData.name + ' (' + recipeData.author.username + ')',
                        };
                        favedRecipes.push(recipe);
                    });

                    let notFavedRecipesData = data.not_faved;
                    let notFavedRecipes = [];
                    $.each(notFavedRecipesData, function (key, recipeData) {
                        let recipe = {
                            'id': recipeData.id,
                            'text': recipeData.name + ' (' + recipeData.author.username + ')',
                        };
                        notFavedRecipes.push(recipe);
                    });
                    return {
                        results: [
                            {
                                "text": "Recettes mises en favoris",
                                "children" : favedRecipes,
                            },
                            {
                                "text": "Autres recettes",
                                "children" : notFavedRecipes
                            },
                        ]
                    };
                }
            },
        });
    }

    function countMealsNeeded() {
        let startDate = $('#meal_list_startDate').val();
        let endDate = $('#meal_list_endDate').val();
        let firstDayMealsCount = parseInt($('#meal_list_isStartingAtLunch option:selected').val()) === 1 ? 2 : 1;
        let lastDayMealsCount = parseInt($('#meal_list_isEndingAtLunch option:selected').val()) === 0 ? 2 : 1;
        let totalCount = (Math.floor((Date.parse(endDate) - Date.parse(startDate)) / 86400000) - 1) * 2 + firstDayMealsCount + lastDayMealsCount;
        $('#needed-meals-count').html(totalCount);
    }

    function countPlannedMeals() {
        let totalPlannedMealsCount = 0;
        $('#meals .meal-quantity').each(function() {
            if ($(this).val() !== null && $(this).val() !== '' && $(this).val() !== undefined) {
                totalPlannedMealsCount = parseFloat($(this).val()) + totalPlannedMealsCount;
            }
        });
        $('#current-meals-count').html(totalPlannedMealsCount);
    }

    function addFormToCollection($collectionHolderClass) {
        // get container of all meal forms
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
        let $newMealListForm = $('<div class="meal"></div>').append(newForm);
        $collectionHolder.append($newMealListForm)

        // deselect default option on recipe selector
        $newMealListForm.find('select').val([]);
        $newMealListForm.find('select option').prop('selected', false);

        // add a delete link to the new form
        bindMealDeletionToButton($newMealListForm);

        // allow pressing enter to move in meals list
        bindLinebreakToInput($newMealListForm);

        // bring focus to the quantity field when selection a meal
        bindBringFocusToQuantity($newMealListForm);

        // bring focus to the recipe search input on Select2 opening
        bindBringFocusToSelect2Input($newMealListForm);

        // activate Select2 on ingredient selectors
        toggleSelect2OnIngredientSelector();

        // bind meals counting
        bindCountPlannedMealToInput($newMealListForm);
    }

    // get the element that holds the collection of meals
    let $collectionHolder = $('#meals');

    // bind actions to the different elements
    $collectionHolder.children('div').each(function () {
        bindMealDeletionToButton($(this));
        bindLinebreakToInput($(this));
        bindBringFocusToQuantity($(this));
        bindBringFocusToSelect2Input($(this));
    });

    // count the current meals we have and
    // use this as new index when inserting new items
    $collectionHolder.data('index', $collectionHolder.find('.meal').length);

    // activate Select2 on ingredient selectors
    toggleSelect2OnIngredientSelector();

    // count meals and display it on the page
    countMealsNeeded();
    countPlannedMeals();

    // setup min and max dates depending on existing ones
    if($('#meal_list_startDate').val() !== '') {
        $('#meal_list_endDate').attr('min', $('#meal_list_startDate').val());
    }
    if($('#meal_list_endDate').val() !== '') {
        $('#meal_list_startDate').attr('max', $('#meal_list_endDate').val());
    }

    $('body').on('click', '.add_item_link', function (e) {
        let $collectionHolderClass = $(e.currentTarget).data('collectionHolderClass');

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
        $('#meal_list_endDate').attr('min', $('#meal_list_startDate').val());

        countMealsNeeded();
    });

    $('#meal_list_endDate').on('change', function () {
        $('#meal_list_startDate').attr('max', $('#meal_list_endDate').val());
        countMealsNeeded();
    });

    $('#meals .meal-quantity').on('input', function () {
        countPlannedMeals();
    });

    $('.starting-at-selector').on('change', function () {
        countMealsNeeded();
    })
});
