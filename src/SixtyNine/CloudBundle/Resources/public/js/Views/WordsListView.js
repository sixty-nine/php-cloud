/*global SnCloud, Backbone */
void function (config) {

    'use strict';

    SnCloud.Views.WordsList = Mn.View.extend({

        el: 'div.container',
        template: false,

        ui: {
            triggerButton: 'button[data-trigger]',
            closeButton: 'div.modal button.btn-clear',
            colorsModal: 'div.modal[data-id="colors"]',
            colorsSubmitButton: 'div.modal[data-id="colors"] button[type="submit"]',
            colorsPalette: 'div.modal[data-id="colors"] select',
            sortModal: 'div.modal[data-id="sort-words"]',
            sortSubmitButton: 'div.modal[data-id="sort-words"] button[type="submit"]',
            sortModalSortBy: 'div.modal[data-id="sort-words"] select.sort-by',
            sortModalOrder: 'div.modal[data-id="sort-words"] select.order'
        },

        events: {
            'click @ui.triggerButton': 'triggerModal',
            'click @ui.closeButton': 'closeModal',
            'click div.modal-overlay': 'closeModal',
            'click @ui.colorsSubmitButton': 'changeColors',
            'click @ui.sortSubmitButton': 'sortWords'
        },

        triggerModal: function (e) {
            var target = $(e.currentTarget).data('trigger');
            var $modal = $('div.modal[data-id="' + target + '"]');
            $modal.addClass('active');
        },

        closeModal: function (e) {
            e.preventDefault();
            $(e.currentTarget).closest('div.modal').removeClass('active');
        },

        changeColors: function (e) {
            e.preventDefault();

            var paletteId = this.ui.colorsPalette.find(':selected').val();

            $(this.ui.colorsModal).removeClass('active');

            SnCloud.showSpinner();
            location.href = $(e.currentTarget)
                .data('url')
                .replace('9999', paletteId)
            ;
        },

        sortWords: function (e) {
            e.preventDefault();

            $(this.ui.sortModal).removeClass('active');

            SnCloud.showSpinner();

            this.collection
                .sortWords(
                    this.ui.sortModalSortBy.find(':selected').val(),
                    this.ui.sortModalOrder.find(':selected').val()
                )
                .then(function () {
                    SnCloud.hideSpinner();
                });
        }
    });

    SnCloud.Views.WordView = Mn.View.extend({

        className: 'word',
        template: '#sn-cloud-words-item-template',

        ui: {
            orientationToggle: 'span.orientation',
            countMinus: 'span.count span.minus',
            countPlus: 'span.count span.plus'
        },

        events: {
            'click @ui.orientationToggle': 'toggleOrientation',
            'click span.remove': 'removeWord',
            'click @ui.countPlus': 'incCount',
            'click @ui.countMinus': 'decCount'
        },

        initialize: function () {
            this.listenTo(this.model, 'change', this.render);
        },

        onRender: function () {
            this.$el.attr('data-id', this.model.get('id'));
        },

        removeWord: function (e) {
            if (SnCloud.fn.confirm(config.translations.areYouSure)) {
                this.model.destroy({wait: true});
            }
        },

        toggleOrientation: function (e) {
            var self = this;
            var url = Routing.generate(
                'cloud_api_toggle_word_orientation',
                {id: this.model.collection.listId, wordId: this.model.get('id')}
            );

            SnCloud.showSpinner();
            $.get(url).then(function( data ) {
                SnCloud.hideSpinner();
                self.model.set('orientation', data.orientation);
            });
        },

        incCount: function (e) {
            var id = $(e.currentTarget).parent().parent().attr('data-id');
            var url = Routing.generate(
                'cloud_api_increase_word_count',
                {id: this.model.collection.listId, wordId: this.model.get('id')}
            );

            this.changeCount(url);
        },

        decCount: function (e) {
            var id = $(e.currentTarget).parent().parent().attr('data-id');
            var url = Routing.generate(
                'cloud_api_decrease_word_count',
                {id: this.model.collection.listId, wordId: this.model.get('id')}
            );

            this.changeCount(url);
        },

        changeCount: function (url) {
            var self = this;
            SnCloud.showSpinner();
            $.get(url).then(function( data ) {
                SnCloud.hideSpinner();
                self.model.set('count', data.count);
            });
        }

    });

    SnCloud.Views.WordsView = Mn.CollectionView.extend({
        el: 'section.list-words',
        childView: SnCloud.Views.WordView,
        childViewEvents: {
            'event:render': 'render'
        },

        onRender: function () {

            var source,
                self = this;

            this.$el.sortable({
                items: 'div.word',
                classes: {
                    'ui-sortable-helper': 'drag-source'
                },
                start: function(e, ui) {
                    source = ui.item.index();
                },
                update: function( event, ui ) {
                    var destination = ui.item.index();
                    self.collection.swapPositions(source, destination);
                }
            });
        }

    });

}(SnCloud.config);
