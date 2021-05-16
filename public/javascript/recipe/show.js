$(document).ready(function () {
    // mark the recipe as favorite
    $('#toggle-favorite-button').on('click', function () {
        let checkbox = $('form#toggle-favorite #mark_recipe_as_favorite_isMarkedAsFavorite');
        checkbox.prop("checked", !checkbox.prop("checked"));
        $('form#toggle-favorite').submit();
    });

    // display ingredients quantity according to portions count
    let initialPortionsCount = $('select#ingredients-quantity').val();
    updateIngredientsQuantity(initialPortionsCount);
    $('select#ingredients-quantity').on('change', function () {
        let portionsCount = $(this).val();
        updateIngredientsQuantity(portionsCount);
    });
});

function updateIngredientsQuantity(portionsCount) {
    $('#ingredients input').each(function (index) {
        let inputLabel = $('label[for="' + $(this).attr('id') + '"]');
        let quantityElement = $(inputLabel).children('span.quantity');
        let quantityForOnePortion = quantityElement.data('quantity-for-one-portion');
        quantityElement.html(quantityForOnePortion * portionsCount);
    });
}
