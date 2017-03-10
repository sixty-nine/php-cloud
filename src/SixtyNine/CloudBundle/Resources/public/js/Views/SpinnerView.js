/*global SnCloud, Backbone */
void function (config) {

    'use strict';


    var SpinnerView = Backbone.View.extend({

        className: 'spinner-container',

        events: {
        },

        initialize: function () {
            $('body').append(this.render().el);
        },

        render: function () {
            this.$el.append('<div class="spinner"> <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div> </div>');
            return this;
        }

    });

    var spinner = false;

    SnCloud.Views.showSpinner = function () {
        if (!spinner) {
            spinner = new SpinnerView();
        }
    };

    SnCloud.Views.hideSpinner = function () {
        if (spinner) {
            spinner.remove();
            spinner = false;
        }
    };

}(SnCloud.config);
