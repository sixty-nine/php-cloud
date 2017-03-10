/*global SnCloud, Backbone */
void function (config) {

    'use strict';

    SnCloud.Views.WordsList = Mn.View.extend({

        el: 'div.container',
        template: false,

        ui: {
            orientationToggle: 'span.orientation',
            colorsSubmitButton: '#colorModal button[type="submit"]'
        },

        events: {
            'click @ui.orientationToggle': 'toggleOrientation',
            'click @ui.colorsSubmitButton': 'changeColors',
            'click span.remove': 'removeWord'
        },

        removeWord: function (e) {

            if (!SnCloud.fn.confirm(config.translations.areYouSure)) return;

            SnCloud.showSpinner();

            var url = $(e.currentTarget).data('url'),
                id = $(e.currentTarget).parent().data('id');

            location.href = url.replace('9999', id);
        },

        toggleOrientation: function (e) {
            SnCloud.showSpinner();
            location.href = $(e.currentTarget).data('url');
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
        template: '#sn-cloud-words-item-template'
    });
    SnCloud.Views.WordsView = Mn.CollectionView.extend({
        el: 'section.list-words',
        childView: SnCloud.Views.WordView
    });

}(SnCloud.config);
