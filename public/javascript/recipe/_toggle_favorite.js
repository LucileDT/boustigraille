$(document).ready(function () {
    let toggleFavoriteButtons = $('.toggle-favorite-button');

    // show icon depending on favorite status
    toggleFavoriteButtons.each(function () {
        let icon = $("<i></i>");
        icon.addClass('action-icon');

        if ($(this).data('marked-as-favorite')) {
            icon.addClass('ri-heart-fill');
            icon.attr('title', 'Retirer de mes favoris');
        } else {
            icon.addClass('ri-heart-add-line');
            icon.attr('title', 'Ajouter à mes favoris');
        }
        icon.tooltip();
        $(this).append(icon);
    });

    // mark the recipe as favorite
    toggleFavoriteButtons.on('click', function () {
        let toggleFavoriteButton = $(this);
        let toggleUrl = toggleFavoriteButton.data('url');
        $.ajax({
            url: toggleUrl,
            method: 'POST',
            dataType: 'json',
        }).done(function() {
            let icon = $(toggleFavoriteButton).find('i');
            let recipeWasFaved = toggleFavoriteButton.data('marked-as-favorite');
            toggleFavoriteButton.data('marked-as-favorite', !recipeWasFaved);

            if (toggleFavoriteButton.data('marked-as-favorite')) {
                icon.addClass('ri-heart-fill');
                icon.removeClass('ri-heart-add-line');
                icon.attr('title', 'Retirer de mes favoris');
            } else {
                icon.addClass('ri-heart-add-line');
                icon.removeClass('ri-heart-fill');
                icon.attr('title', 'Ajouter à mes favoris');
            }

            icon.tooltip('dispose');
            icon.tooltip();
            icon.tooltip('show');
        });
    });
});
