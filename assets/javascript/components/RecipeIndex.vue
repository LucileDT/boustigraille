<template>
    <div class="p-4 bg-light mb-5">
        <h3 class="mb-3">Filtres</h3>
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="mb-3">
                    <label for="filter_recipe_name" class="form-label">Nom de la recette</label>
                    <input
                        type="text"
                        id="filter_recipe_name"
                        name="filter_recipe[name]"
                        placeholder="Patates sautées"
                        class="form-control form-control"
                        v-model="recipeNameFilter"
                    >
                </div>
            </div>
            <div class="col-12 col-md">
                <div class="mb-3">
                    <label class="form-label" for="filter_recipe_tags">Tags</label>
                    <select
                        id="filter_recipe_tags"
                        name="filter_recipe[tags][]"
                        class="form-control"
                        multiple
                        v-model="recipeTagFilter"
                    >
                        <option v-for="tag in tags" :value="tag.id">{{ tag.label }}</option>
                    </select>
                    <div class="form-text mb-0 help-text">
                        Par défaut, seules les recettes végétariennes et véganes sont affichées.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="klassy-cards-container" class="auto-grid">
        <RecipeCard :connected-user="JSON.parse(props.connectedUser)" :recipe="recipe" v-for="recipe in filteredRecipes" :key="recipe.id"></RecipeCard>
    </div>
</template>

<script setup>
    import { onBeforeMount, onMounted, ref, computed } from 'vue';
    import RecipeCard from './RecipeCard.vue';
    import $ from "jquery";

    const props = defineProps({
        connectedUser: String
    });
    const recipes = ref(null);
    const tags = ref(null);
    const recipeNameFilter = ref('');
    const recipeTagFilter = ref([]);

    onBeforeMount(() => {
        fetch('/api/recipe/').then(function (httpResponse) {
            return httpResponse.json();
        }).then(function (allRecipes) {
            recipes.value = allRecipes;
        });

        fetch('/api/tag/').then(function (httpResponse) {
            return httpResponse.json();
        }).then(function (allTags) {
            tags.value = allTags;
            recipeTagFilter.value.push(
                allTags.find(tag => {
                    return tag.label === 'Végé'
                }).id
            );
        });
    });

    onMounted(() => {
        // activate Select2 on tags selector
        $('#filter_recipe_tags').select2({
            theme: "bootstrap-5",
            tags: false,
        });
        $('#filter_recipe_tags').on('change', ($event) => {
            let selection = $('#filter_recipe_tags').find(':selected').toArray();
            recipeTagFilter.value = selection.map(selected => {
                return parseInt(selected.value);
            });
        });
    });

    const filteredRecipes = computed(() => {
        if (recipes.value === null) {
            return [];
        }

        return recipes.value.filter((recipe) => {
            let recipeTagIds = recipe.tags.map(tag => { return tag.id });

            let tagFilter =
                recipeTagFilter.value.length === 0 ||
                recipeTagFilter.value.every(filteredTagId => {
                    return recipeTagIds.includes(filteredTagId);
                });

            let normalizedRecipeName = removeAccents(recipe.name).toLowerCase();
            let normalizedFilter = removeAccents(recipeNameFilter.value).toLowerCase();

            let nameFilter = normalizedRecipeName.includes(normalizedFilter);

            return nameFilter && tagFilter;
        });
    });

    const removeAccents = function(string) {
        return string.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }
</script>