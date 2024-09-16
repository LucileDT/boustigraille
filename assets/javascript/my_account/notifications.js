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
});
