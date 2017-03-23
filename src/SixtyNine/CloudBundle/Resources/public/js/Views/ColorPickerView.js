/*global SnCloud, Backbone */
void function (config) {

    'use strict';

    SnCloud.Views.ColorPickerView = Mn.View.extend({

        template: '#sn-color-picker-template',

        ui: {
            modal: 'div.modal',
            input: 'input.color-input',
            okButton: 'button.btn.ok',
            nokButton: 'button.btn.nok'
        },

        events: {
            'click @ui.nokButton': 'cancel',
            'click div.modal-overlay': 'cancel',
            'click @ui.okButton': 'confirm'
        },

        initialize: function (options) {
            this.color = options.color;
            this.promise = $.Deferred();
            $('body').append(this.render().el);
        },

        getPromise: function () {
            return this.promise;
        },

        onRender: function () {
            this.ui.input.val(SnCloud.fn.rgb2hex(this.color));
            new Huebee(this.ui.input[0], {
                setBGColor: true,
                saturations: 2,
                notation: 'hex'
            });
        },

        confirm: function () {
            this.promise.resolve(this.ui.input.val());
            this.destroy();
        },

        cancel: function () {
            this.promise.reject();
            this.destroy();
        }
    });

}(SnCloud.config);
