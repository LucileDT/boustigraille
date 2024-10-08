import '../../styles/recipe/show.scss';
import $ from "jquery";

$(document).ready(function () {
    // display ingredients quantity according to portions count
    let initialPortionsCount = $('select#ingredients-quantity').val();
    updateIngredientsQuantity(initialPortionsCount);
    $('select#ingredients-quantity').on('change', function () {
        let portionsCount = $(this).val();
        updateIngredientsQuantity(portionsCount);
    });

    $('#expand-nutritional-information').on('click', function () {
        $('.chevron').toggleClass('d-none');
    });

    $('#review-recipe').on('submit', function (event) {
        event.preventDefault();
        let submitUrl = $(this).data('submit-url');
        let formData = $(this).serialize();
        $.ajax({
            url: submitUrl,
            method: 'POST',
            dataType: 'json',
            data: formData,
        }).done(function() {
            location.reload();
        });
    })
});

function updateIngredientsQuantity(portionsCount) {
    $('#ingredients input').each(function (index) {
        let $inputLabel = $('label[for="' + $(this).attr('id') + '"]');
        let $quantityElement = $inputLabel.children('span.label').children('span.quantity');

        let quantityForOnePortion = $quantityElement.data('quantity-for-one-portion');
        $quantityElement.html(quantityForOnePortion * portionsCount);

        let $quantityForUnitElement = $inputLabel.children('span.quantity-for-unit');
        if ($quantityForUnitElement.length > 0) {
            let unitSize = $quantityForUnitElement.data('unit-size');
            $quantityForUnitElement.html(quantityForOnePortion * portionsCount * unitSize);
        }
    });
}
