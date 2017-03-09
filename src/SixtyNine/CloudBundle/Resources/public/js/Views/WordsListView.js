'use strict';

SnCloud.Views.WordsList = Backbone.View.extend({

    el: 'div.container',

    events: {
        'click table.words span.label': 'toggleOrientation',
        'click a[data-type="palette"]': 'setPalette',
        'click a[data-type="palette-type"]': 'changeColors'
    },

    initialize: function() {
        this.curPalette = $('a[data-type="palette"]:first').data('id');
    },

    toggleOrientation: function (e) {
        var wordId = $(e.currentTarget).data('id');
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

