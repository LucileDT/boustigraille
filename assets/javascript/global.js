$(document).ready(function () {
    // Toggle all tooltips of the project
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
});

function focusNextElement() {
    // add all elements we want to include in our selection
    let focussableElements = 'a:not([disabled]), button:not([disabled]), input:not([disabled]), select:not([disabled]), [tabindex]:not([disabled]):not([tabindex="-1"])';

    if (document.activeElement && document.activeElement.form) {
        let focussable = Array.prototype.filter.call(document.activeElement.form.querySelectorAll(focussableElements), function (element) {
            //check for visibility while always include the current activeElement
            return element.offsetWidth > 0 || element.offsetHeight > 0 || element === document.activeElement
        });

        let index = focussable.indexOf(document.activeElement);

        if (index > -1) {
            let nextElement = focussable[index + 1] || focussable[0];
            nextElement.focus();
        }
    }
}
