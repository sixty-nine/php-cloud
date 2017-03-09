$(function () {

    'use strict';

    var View = Backbone.View.extend({
        el: 'div.container',

        events: {
            "click button": "test"
        },

        initialize: function() {
            console.log('coucou');
        },

        test: function () {
            console.log('test');
        }
    });

    new View().render();
});
