import '../../styles/recipe/show.scss';

$(document).ready(function () {
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
