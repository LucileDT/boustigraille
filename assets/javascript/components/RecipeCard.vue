<template>
    <div
        class="card klassy-cafe-card"
        :style="{ 'background-image': 'url(' + props.recipe.main_picture_url + ')' }"
    >
        <div class="top-box">
            <h6 class="energy-box d-flex justify-content-center align-items-center">
                <span>
                    <span class="energy-count">
                        {{ Math.round(props.recipe.energy) }}
                    </span><br>
                    <span class="energy-unit">
                        kcal
                    </span>
                </span>
            </h6>
            <RecipeFavoriteToggle
                    v-if="props.connectedUser"
                    :connected-user="props.connectedUser"
                    :recipe="props.recipe"
            ></RecipeFavoriteToggle>
        </div>
        <div class="info-box d-flex flex-column justify-content-between">
            <div>
                <h1 class="title">
                    <i :class="veganClass" :title="veganTitle" data-bs-toggle="tooltip"></i>
                    {{ props.recipe.name }}
                </h1>

                <p v-if="props.recipe.can_view_author_username || props.recipe.author.do_show_username_on_recipe" class="description">
                    Par {{ props.recipe.author.username }}
                </p>
                <div v-else class="mb-3"></div>

                <div id="recipe-tags">
                    <span v-for="tag in props.recipe.tags" class="recipe-tag">
                        {{ tag.label }}
                    </span>
                </div>
                <div id="recipe-duration" class="text-white mt-2">
                    <strong>
                        <small>Temps total&nbsp;:</small>
                    </strong>
                    {{ props.recipe.full_duration }}
                </div>
                <div id="recipe-difficulty-level" class="text-white">
                    <strong>
                        <small>Difficulté&nbsp;:</small>
                    </strong> <small>
                        {{ props.recipe.difficulty_level.label ? props.recipe.difficulty_level.label : '-' }}
                    </small>
                </div>
            </div>
            <div class="main-text-button">
                <div class="row g-2">
                    <div class="col">
                        <a
                                class="recipe-details-page-button btn btn-sm mb-2"
                                :href="props.recipe.recipe_show_path"
                        >
                            Voir
                        </a>
                    </div>
                    <div v-if="props.connectedUser != null" class="col">
                        <a
                                class="btn btn-sm mb-2"
                                :href="props.recipe.recipe_edit_path"
                        >
                            Éditer
                        </a>
                    </div>
                </div>
                <div v-if="props.connectedUser != null" class="row">
                    <div class="col">
                        <a
                                class="btn btn-sm btn-outline"
                                :href="props.recipe.recipe_new_path"
                        >
                            Copier la recette
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { computed } from 'vue';
    import RecipeFavoriteToggle from './RecipeFavoriteToggle.vue';

    const props = defineProps({
        connectedUser: Object|null,
        recipe: Object|null
    });

    const isVegan = function() {
        return props.recipe.tags.filter((tag) => {
            return tag.label === 'Vegan'
        }).length !== 0;
    }

    const isVege = function() {
        return props.recipe.tags.filter((tag) => {
           return tag.label === 'Végé';
        }).length !== 0;
    }

    const veganClass = computed(() => {
        if (isVegan()) {
            return 'ri-plant-fill text-success';
        } else if (isVege()) {
            return 'ri-seedling-fill text-success';
        } else {
            return 'ri-skull-line';
        }
    });

    const veganTitle = computed(() => {
        if (isVegan()) {
            return 'Vegan';
        } else if (isVege()) {
            return 'Végé';
        } else {
            return 'Carné';
        }
    });
</script>