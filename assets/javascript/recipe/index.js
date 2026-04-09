import $ from "jquery";
import { createApp } from 'vue';
import RecipeIndex from "../components/RecipeIndex.vue";

const mountEl = document.querySelector("#recipe-index");
createApp(RecipeIndex, { ...mountEl.dataset }
).mount("#recipe-index");

$(document).ready(function () {
    // // activate Select2 on tags selector
    // $('#filter_recipe_tags').select2({
    //     theme: "bootstrap-5",
    //     tags: false,
    // });

    $('#filter-recipe').on('submit', function (event) {
        event.preventDefault();
        let formData = $(this).serialize();

        let currentLocation = window.location;
        let search = new URLSearchParams(formData);
        let url = new URL(currentLocation.origin + $(this).attr('action') + '?' + search.toString())

        window.location.href = url;
    })
});
