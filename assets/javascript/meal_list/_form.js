const $ = require("jquery");
import '../../styles/meal_list/_form.scss';
import { toggleButtonTooltip, toggleFavorite } from '../recipe/_toggle_favorite';

$(document).ready(function () {
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

                let $collectionHolderClass = $('#add-recipe-button').data('collectionHolderClass');
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

    function addSuggestedRecipeToMealList(button) {
        // add a new meal form
        let $collectionHolderClass = $('#add-recipe-button').data('collectionHolderClass');
        addFormToCollection($collectionHolderClass);

        // make selector have the good recipe selected
        let recipeId = button.data('recipe-id');
        let recipeSelect = $('#meals .meal-select:last');
        recipeSelect.val(recipeId);
        recipeSelect.trigger('change');

        // focus on its input
        recipeSelect.parents('.meal').find('.meal-quantity').focus();
    }

    function showSuggestedRecipes() {
        let recipesContainer = $('#suggested-recipes');
        let urlSuggestedRecipes = recipesContainer.data('url');
        $.ajax({
            url: urlSuggestedRecipes,
            method: 'GET',
            dataType: 'json',
            beforeSend: function (jqXHR, settings) {
                $('#suggested-recipes-loader').removeClass('d-none');
                $('#suggested-recipes-empty').addClass('d-none');
                $('.suggested-recipe').remove();
            },
        }).done(function (data) {
            // if no suggested recipes, display information message
            if (data.length === 0) {
                $('#suggested-recipes-loader').addClass('d-none');
                $('#suggested-recipes-empty').removeClass('d-none');
                return;
            }

            $('#suggested-recipes-loader').addClass('d-none');

            let dummyRecipe = recipesContainer.find('#dummy-recipe > .klassy-cafe-card');
            $(data).each(function () {
                let newRecipe = dummyRecipe.clone();
                newRecipe.removeClass('mb-4');
                newRecipe.addClass('suggested-recipe');

                // change recipe card content accordingly
                newRecipe.find('.energy-count').html(Math.round(this.energy));
                newRecipe.find('.title').html(this.name);
                newRecipe.find('.title').html(this.name);
                if (this.main_picture_filename) {
                    newRecipe.css('background-image', 'url("/uploads/pictures/' + this.main_picture_filename + '")');
                } else {
                    newRecipe.css('background-image', 'url("/build/image/default-recipe-main-picture.jpg")');
                }

                if (this.author) {
                    newRecipe.find('.description').html('Par ' + this.author.username);
                } else {
                    newRecipe.find('.description').html('');
                }
                newRecipe.find('.main-text-button > .row').remove();
                let button = $('<button>')
                    .addClass('btn')
                    .addClass('btn-sm')
                    .addClass('btn-outline')
                    .addClass('add-to-meal-list')
                    .attr('type', 'button')
                    .data('recipe-id', this.id)
                    .html('Ajouter à la liste de repas')
                ;
                button.on('click', function () {
                    addSuggestedRecipeToMealList($(this));
                });
                let row = $('<div>').addClass('row');
                let col = $('<div>').addClass('col');
                col.append(button);
                row.append(col);
                newRecipe.find('.main-text-button').append(row);

                // update toggling fav url
                let url = newRecipe.find('.toggle-favorite-button').data('url');
                let updatedUrl = url.replace(/\/([0-9])+$/, '/' + this.id);
                newRecipe.find('.toggle-favorite-button').data('url', updatedUrl);
                newRecipe.find('.toggle-favorite-button').attr('data-url', updatedUrl);

                // recipes here are always marked as favorites
                newRecipe.find('.toggle-favorite-button').attr('data-marked-as-favorite', 1);

                // make fav button working
                toggleButtonTooltip(newRecipe.find('.toggle-favorite-button'), newRecipe.find('.action-icon'));
                newRecipe.find('.toggle-favorite-button').on('click', function () {
                    toggleFavorite(this);
                    showSuggestedRecipes();
                });
                recipesContainer.append(newRecipe);
            });

            // add placeholder if there is less than 6 suggested recipes
            if (data.length < 6) {
                for (let i = 0; i < 6 - data.length; i++) {
                    let emptyRecipe = $('<div>')
                        .addClass('card')
                        .addClass('klassy-cafe-card')
                        .addClass('mx-0')
                        .addClass('suggested-recipe')
                        .addClass('bg-light')
                        .addClass('d-flex')
                        .addClass('align-items-center')
                        .addClass('justify-content-center')
                        .addClass('text-center')
                    ;
                    let infoText = $('<small>')
                        .addClass('text-muted')
                        .addClass('fst-italic')
                        .html('Ajoutez d\'autres recettes à vos favoris pour avoir plus de suggestions')
                    ;
                    emptyRecipe.append(infoText);
                    recipesContainer.append(emptyRecipe);
                }
            }
        });
    }

    // load suggested recipes
    showSuggestedRecipes();

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

    $('body').on('click', '#add-recipe-button', function (e) {
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
    });
});
