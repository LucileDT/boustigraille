jQuery(document).ready(function () {
    function bindIngredientDeletionToButton($ingredientForm) {
        $ingredientForm.find('.ingredient-deletion-button').on('click', function (e) {
            // remove the ingredient from the form
            $ingredientForm.remove();
        });
    }

    function addFormToCollection($collectionHolderClass) {
        // get container of all ingredient forms
        var $collectionHolder = $('.' + $collectionHolderClass);
        // get form data-prototype
        var prototype = $collectionHolder.data('prototype');
        // get the new index
        var index = $collectionHolder.data('index');

        var newForm = prototype;
        // replace '__name__label__' in the prototype's HTML to
        // instead be a number based on how many items we have
        newForm = newForm.replace(/__name__label__/g, index);

        // replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        newForm = newForm.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // display the new form in the page
        var $newIngredientForm = $('<div class="ingredient"></div>').append(newForm);
        $collectionHolder.append($newIngredientForm)

        // add a delete link to the new form
        bindIngredientDeletionToButton($newIngredientForm);
    }

    // get the element that holds the collection of ingredients
    var $collectionHolder = $('#ingredients');

    // bind deletion button to existing ingredient forms
    $collectionHolder.children('div').each(function () {
        bindIngredientDeletionToButton($(this));
    });

    // count the current ingredients we have and
    // use this as new index when inserting new items
    $collectionHolder.data('index', $collectionHolder.find('.ingredient').length);

    $('body').on('click', '.add_item_link', function (e) {
        var $collectionHolderClass = $(e.currentTarget).data('collectionHolderClass');
        console.debug($collectionHolderClass);
        // add a new ingredient form
        addFormToCollection($collectionHolderClass);
    })
});
