const $ = require("jquery");

$(document).ready(function () {
    $('button.accept-access').on('click', function () {
        let acceptUrl = $(this).data('url-accept-access');
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
    $('button.refuse-access').on('click', function () {
        let refuseUrl = $(this).data('url-refuse-access');
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
