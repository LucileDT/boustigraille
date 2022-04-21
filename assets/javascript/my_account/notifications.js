const $ = require("jquery");

$(document).ready(function () {
    $('.toggle-read-notification').on('click', function () {
        let toggleUrl = $(this).data('url-toggle-read-notification');
        if (toggleUrl !== undefined) {
            $.ajax({
                url: toggleUrl,
                method: 'POST',
                dataType: 'json',
            }).done(function () {
                location.reload();
            });
        }
    });
    $('button.accept-meal-list-access').on('click', function () {
        let acceptUrl = $(this).data('url-accept-meal-list-access');
        if (acceptUrl !== undefined) {
            $.ajax({
                url: acceptUrl,
                method: 'POST',
                dataType: 'json',
            }).done(function () {
                location.reload();
            });
        } else {
            console.error('URL is not defined');
        }
    });
    $('button.refuse-meal-list-access').on('click', function () {
        let refuseUrl = $(this).data('url-refuse-meal-list-access');
        if (refuseUrl !== undefined) {
            $.ajax({
                url: refuseUrl,
                method: 'POST',
                dataType: 'json',
            }).done(function () {
                location.reload();
            });
        } else {
            console.error('URL is not defined');
        }
    });
});
