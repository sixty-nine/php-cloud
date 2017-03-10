/*global SnCloud, Backbone */
void function (config) {

    'use strict';

    SnCloud.Views.WordsList = Mn.View.extend({

        el: 'div.container',
        template: false,

        ui: {
            colorsSubmitButton: '#colorModal button[type="submit"]'
        },

        events: {
            'click @ui.colorsSubmitButton': 'changeColors'
        },

        changeColors: function (e) {
            e.preventDefault();

            var paletteId = $('#colorModal select :selected').val();

            $('#colorModal').modal('hide');
            SnCloud.showSpinner();
            location.href = $(e.currentTarget)
                .data('url')
                .replace('9999', paletteId)
            ;
        }
    });

    SnCloud.Views.WordView = Mn.View.extend({

        className: 'word',
        template: '#sn-cloud-words-item-template',

        ui: {
            orientationToggle: 'span.orientation'
        },

        events: {
            'click @ui.orientationToggle': 'toggleOrientation',
            'click span.remove': 'removeWord'
        },

        initialize: function () {
            this.listenTo(this.model, 'change', this.render);
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
        }

    });

    SnCloud.Views.WordsView = Mn.CollectionView.extend({
        el: 'section.list-words',
        childView: SnCloud.Views.WordView
    });

}(SnCloud.config);
