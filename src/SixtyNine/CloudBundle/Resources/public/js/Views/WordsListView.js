/*global SnCloud, Backbone */
void function (config) {

    'use strict';

    SnCloud.Views.WordsList = Backbone.View.extend({

        el: 'div.container',

        events: {
            'click span.orientation': 'toggleOrientation',
            'click #colorModal button[type="submit"]': 'changeColors',
            'click span.remove': 'removeWord'
        },

        initialize: function () {
        },

        removeWord: function (e) {

            // TODO: do it better :)
            if (!confirm('Are you sure ?')) return;

            SnCloud.Views.showSpinner();

            var url = $(e.currentTarget).data('url'),
                id = $(e.currentTarget).parent().data('id');

            location.href = url.replace('9999', id);
        },

        toggleOrientation: function (e) {
            SnCloud.Views.showSpinner();
            location.href = $(e.currentTarget).data('url');
        },

        changeColors: function (e) {
            e.preventDefault();

            var paletteId = $('#colorModal select :selected').val();

            $('#colorModal').modal('hide');
            SnCloud.Views.showSpinner();
            location.href = $(e.currentTarget)
                .data('url')
                .replace('9999', paletteId)
            ;
        }
    });

}(SnCloud.config);
