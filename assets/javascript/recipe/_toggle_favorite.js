const $ = require('jquery');

export function toggleButtonTooltip(buttonContainer, favoriteButton) {
    if ($(buttonContainer).data('marked-as-favorite')) {
        favoriteButton.addClass('ri-heart-fill');
        favoriteButton.removeClass('ri-heart-add-line');
        favoriteButton.attr('title', 'Retirer de mes favoris');
    } else {
        favoriteButton.addClass('ri-heart-add-line');
        favoriteButton.removeClass('ri-heart-fill');
        favoriteButton.attr('title', 'Ajouter à mes favoris');
    }
    favoriteButton.tooltip();
}
export function createFavoriteButton(buttonContainer) {
    let icon = $("<i></i>");
    icon.addClass('action-icon');

    toggleButtonTooltip(buttonContainer, icon);
    $(buttonContainer).append(icon);
}

export function toggleFavorite(favoriteButton) {
    let toggleFavoriteButton = $(favoriteButton);
    let toggleUrl = toggleFavoriteButton.data('url');
    $.ajax({
        url: toggleUrl,
        method: 'POST',
        dataType: 'json',
    }).done(function() {
        let $icon = $(toggleFavoriteButton).find('i');
        $icon.tooltip('dispose');
        let recipeWasFaved = toggleFavoriteButton.data('marked-as-favorite');
        toggleFavoriteButton.data('marked-as-favorite', !recipeWasFaved);

        if (toggleFavoriteButton.data('marked-as-favorite')) {
            $icon.addClass('ri-heart-fill');
            $icon.removeClass('ri-heart-add-line');
            $icon.attr('title', 'Retirer de mes favoris');
        } else {
            $icon.addClass('ri-heart-add-line');
            $icon.removeClass('ri-heart-fill');
            $icon.attr('title', 'Ajouter à mes favoris');
        }

        $icon.tooltip();
        $icon.tooltip('show');
    });
}

$(document).ready(function () {
    let toggleFavoriteButtons = $('.toggle-favorite-button');

    // show icon depending on favorite status
    toggleFavoriteButtons.each(function () {
        createFavoriteButton(this)
    });

    // mark the recipe as favorite
    toggleFavoriteButtons.on('click', function () {
        toggleFavorite(this)
    });
});
