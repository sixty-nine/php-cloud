'use strict';

SnCloud.Views.WordsList = Backbone.View.extend({

    el: 'div.container',

    events: {
        'click span.orientation': 'toggleOrientation',
//        'click a[data-type="palette"]': 'setPalette',
//        'click a[data-type="palette-type"]': 'changeColors',
        'click span.remove': 'removeWord'
    },

    initialize: function() {
        this.curPalette = $('a[data-type="palette"]:first').data('id');
    },

    removeWord: function (e) {

        // TODO: do it better :)
        if (!confirm('Are you sure ?')) return;

        var url = $(e.currentTarget).data('url'),
            id = $(e.currentTarget).parent().data('id');

        location.href = url.replace('9999', id);
    },

    toggleOrientation: function (e) {
        var wordId = $(e.currentTarget).parent().data('id');
        console.log('toggle', wordId);
    },

    setPalette: function (e) {
        this.curPalette = $(e.currentTarget).data('id');
    },

    changeColors: function (e) {
        e.preventDefault();
        var url = $(e.currentTarget).attr('href').replace('9999', this.curPalette);
        location.href = url;
    }
});

