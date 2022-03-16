import '../../styles/meal_list/_meal_list_card.scss';

$(document).ready(function () {
    let groceryListForm = $('#grocery-list-form');

    function toggleGroceryFormButton() {
        let groceryListFormButton = groceryListForm.find('button');
        let groceryListFormButtonTooltipContainer = groceryListFormButton.parent();
        if ($('.meal-list.selected').length === 0) {
            groceryListFormButton.prop('disabled', true);
            groceryListFormButtonTooltipContainer.tooltip();
        } else {
            groceryListFormButtonTooltipContainer.tooltip('dispose');
            groceryListFormButton.prop('disabled', false);
        }
    }

    function toggleMealListSelection(mealList, selectionButton) {
        mealList.toggleClass('selected');
        selectionButton.toggleClass('btn-primary');
        selectionButton.toggleClass('btn-outline-primary');
        selectionButton.find('span').toggleClass('d-none');
        toggleGroceryFormButton();
    }

    $('.meal-list-selector').on('click', function () {
        toggleMealListSelection($(this).parents('.meal-list'), $(this));
    });

    $('.meal-list.card').dblclick(function () {
        toggleMealListSelection($(this), $(this).find('.meal-list-selector'));
    });

    // get selected meal lists before submitting
    groceryListForm.on('submit', function (event) {
        groceryListForm.find('input:checkbox').remove();
        $('.meal-list.selected').each(function () {
            let groceryListId = $(this).data('meal-list-id');
            let mealListInput = $('<input>')
                .attr('id', 'grocery_list_mealLists_' + groceryListId)
                .attr('name', 'grocery_list[mealLists][]')
                .attr('type', 'checkbox')
                .addClass('form-check-input')
                .addClass('d-none')
                .val(groceryListId)
                .prop('checked', true);
            groceryListForm.append(mealListInput);
        });
    });

    toggleGroceryFormButton();
});
