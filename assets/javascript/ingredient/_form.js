const $ = require("jquery");
import '../../styles/recipe/_form.scss';

$(document).ready(function () {
    // activate Select2 on tags selector
    $('#ingredient_tags').select2({
        theme: "bootstrap-5",
        tags: true, // allows dynamic tag creation
        createTag: function (params) {
            let term = $.trim(params.term);

            if (term === '') { return null; }

            return {
                id: term,
                text: term,
                newTag: true // add additional parameters
            }
        }
    });
});
