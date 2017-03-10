/*global SnCloud, Backbone */
void function (config) {

    'use strict';


    var SpinnerView = Mn.View.extend({

        className: 'spinner-container',
        template: _.template('<div class="spinner"> <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div> </div>'),

        initialize: function () {
            $('body').append(this.render().el);
        }
    });

    var spinner = false;

    SnCloud.showSpinner = function () {
        if (!spinner) {
            spinner = new SpinnerView();
        }
    };

    SnCloud.hideSpinner = function () {
        if (spinner) {
            spinner.remove();
            spinner = false;
        }
    };

}(SnCloud.config);
