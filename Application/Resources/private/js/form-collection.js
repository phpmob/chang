/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @author Arnaud Langlade <arn0d.dev@gmail.com>
 */
!function ($) {
    "use strict";

    /**
     * Collection Form plugin
     *
     * @param element
     * @constructor
     */
    const CollectionForm = function (element) {
        this.$element = $(element);
        this.$list = this.$element.find('[data-form-collection="list"]:first');
        this.count = this.$list.children().length;
        this.lastChoice = null;

        this.$element.on(
            'click',
            '[data-form-collection="add"]:first',
            $.proxy(this.addItem, this)
        );

        this.$element.on(
            'click',
            '[data-form-collection="delete"]',
            $.proxy(this.deleteItem, this)
        );

        this.$element.on(
            'change',
            '[data-form-collection="update"]',
            $.proxy(this.updateItem, this)
        );

        $(document).on(
            'change',
            '[data-form-prototype="update"]',
            $.proxy(this.updatePrototype, this)
        );
    };

    CollectionForm.prototype = {
        constructor: CollectionForm,

        /**
         * Add a item to the collection.
         * @param event
         */
        addItem: function (event) {
            event.preventDefault();

            let prototype = this.$element.data('prototype');

            prototype = prototype.replace(
                /__name__/g,
                this.count
            );

            let $box = $($('<div/>').html(prototype).text());
            let $adderBox = this.$list.find('[data-form-collection-item-add-box]');

            if ($adderBox.length) {
                $adderBox.before($box);
            } else {
                this.$list.append($box);
            }

            this.count = this.count + 1;

            $(document).trigger('collection-form-add', [$box]);
        },

        /**
         * Update item from the collection
         */
        updateItem: function (event) {
            event.preventDefault();
            const $element = $(event.currentTarget),
                url = $element.data('form-url'),
                value = $element.val(),
                $container = $element.closest('[data-form-collection="item"]'),
                index = $container.data('form-collection-index'),
                position = $container.data('form-collection-index');
            if (url) {
                $container.load(url, { 'id': value, 'position': position });
            } else {
                let prototype = this.$element.find('[data-form-prototype="' + value + '"]').val();

                prototype = prototype.replace(
                    /__name__/g,
                    index
                );

                $container.replaceWith(prototype);
            }
            $(document).trigger('collection-form-update', [$(event.currentTarget)]);
        },

        /**
         * Delete item from the collection
         * @param event
         */
        deleteItem: function (event) {
            event.preventDefault();
            const $item = $(event.currentTarget).closest('[data-form-collection="item"]');

            $.confirm({
                theme: 'modern',
                title: 'Confirmation',
                icon: 'fas fa-exclamation-triangle',
                content: 'Do you want to continue?',
                buttons: {
                    confirm: {
                        text: 'Yes, I do!',
                        btnClass: 'btn-danger',
                        action: function () {
                            $item.remove();
                        }
                    },
                    cancel: {
                        text: 'No'
                    }
                }
            });

            // empty array for patch collection submit
            // NEED: Chang\Form\Extension\ConvertRawDataFormExtension
            if (this.$element.data('name')) {
                if (!this.$element.find('[data-form-collection="item"]').length) {
                    this.$element.append('<input type="hidden" name="' + this.$element.data('name') + '" value="[]"/>')
                }
            }

            $(document).trigger('collection-form-delete', [$(event.currentTarget)]);
        },

        /**
         * Update the prototype
         * @param event
         */
        updatePrototype: function (event) {
            const $target = $(event.currentTarget);
            let prototypeName = $target.val();

            if (undefined !== $target.data('form-prototype-prefix')) {
                prototypeName = $target.data('form-prototype-prefix') + prototypeName;
            }

            if (null !== this.lastChoice && this.lastChoice !== prototypeName) {
                this.$list.html('');
            }

            this.lastChoice = prototypeName;

            this.$element.data(
                'prototype',
                this.$element.find('[data-form-prototype="' + prototypeName + '"]').val()
            );
        }
    };

    /*
     * Plugin definition
     */

    $.fn.CollectionForm = function (option) {
        return this.each(function () {
            const $this = $(this);
            let data = $this.data('collectionForm');
            let options = typeof option === 'object' && option;

            if (!data) {
                $this.data('collectionForm', new CollectionForm(this, options))
            }
        })
    };

    $.fn.CollectionForm.Constructor = CollectionForm;

    /*
     * Apply to standard CollectionForm elements
     */

    $(document).on('collection-form-add', function (e, addedElement) {
        $(addedElement).find('[data-form-type="collection"]').CollectionForm();
        $(document).trigger('dom-node-inserted', [$(addedElement)]);
    });
}(jQuery);

jQuery(function () {
    jQuery('[data-form-type="collection"]').CollectionForm();

    jQuery(document).on('dom-node-inserted', function (e, scope) {
        jQuery('[data-form-type="collection"]', scope).CollectionForm();
    })
});
