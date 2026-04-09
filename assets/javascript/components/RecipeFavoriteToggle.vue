<template>
    <h6 class="fav-box d-flex justify-content-center align-items-center">
        <span @click="toggleFavorite($event)">
            <i
                    :id="'favorite-icon-' + props.recipe.id"
                    class="action-icon"
                    :class="favoriteIconClass"
                    :data-bs-original-title="favoriteIconTitle"
            ></i>
        </span>
    </h6>
</template>

<script setup>
    import { onMounted, computed, watch } from 'vue';
    import { Tooltip } from 'bootstrap';

    const props = defineProps({
        connectedUser: Object|null,
        recipe: Object|null
    });

    onMounted(() => {
        let favoriteIcon = document.getElementById('favorite-icon-' + props.recipe.id);
        new Tooltip(favoriteIcon);
    });

    const isFavedBy = function(user) {
        return props.recipe.faved_by.filter((favedBy) => {
            return user !== null && user.id === favedBy.id
        }).length !== 0;
    };

    const favoriteIconTitle = computed(() => {
        return isFavedBy(props.connectedUser) ? 'Retirer de mes favoris' : 'Ajouter à mes favoris';
    });

    watch(favoriteIconTitle, (newTitle, oldTitle) => {
        setTimeout(() => {
            let favoriteIcon = document.getElementById('favorite-icon-' + props.recipe.id);
            let oldTooltip = Tooltip.getInstance(favoriteIcon);
            oldTooltip.show();
        }, 150);
    });

    const favoriteIconClass = computed(() => {
        return isFavedBy(props.connectedUser) ? 'ri-heart-fill' : 'ri-heart-add-line';
    });

    const toggleFavorite = function($event) {
        let favoriteIcon = document.getElementById('favorite-icon-' + props.recipe.id);
        let oldTooltip = Tooltip.getInstance(favoriteIcon);
        oldTooltip.hide();

        fetch(props.recipe.toggle_favorite_path, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
        }).then((response) => {
            if (response.ok) {
                if (isFavedBy(props.connectedUser)) {
                    props.recipe.faved_by = props.recipe.faved_by.filter((favedBy) => {
                        return favedBy.id !== props.connectedUser.id;
                    });
                } else {
                    props.recipe.faved_by.push(props.connectedUser);
                }
            } else {
                // error managment
            }
        });
    };
</script>