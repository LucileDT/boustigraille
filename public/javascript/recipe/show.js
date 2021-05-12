$(document).ready(function () {
    $('#toggle-favorite-button').on('click', function () {
        let checkbox = $('form#toggle-favorite #mark_recipe_as_favorite_isMarkedAsFavorite');
        checkbox.prop("checked", !checkbox.prop("checked"));
        $('form#toggle-favorite').submit();
    });
});
