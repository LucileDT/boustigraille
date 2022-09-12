const $ = require("jquery");
$(document).ready(function () {
    // Toggle all tooltips of the project
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    // Check if there is unread notifications
    let unreadNotificationsUrl = $('#top-navbar').data('url-unread-notifications');
    if (unreadNotificationsUrl !== undefined) {
        $.ajax({
            url: unreadNotificationsUrl,
            method: 'GET',
            dataType: 'json',
        }).done(function (data) {
            let userAvatar = $('#user-dropdown > .ri-account-circle-line');
            let notificationsCountContainer = $('#user-notifications .badge');
            if (data.has_notification) {
                userAvatar.addClass('has-notification');
                notificationsCountContainer.removeClass('d-none');
                notificationsCountContainer.html(data.notifications_count)
            } else {
                userAvatar.removeClass('has-notification');
                notificationsCountContainer.addClass('d-none');
            }
        });
    }
});
