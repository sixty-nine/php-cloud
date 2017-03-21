/*global SnCloud, Backbone */
void function (config) {

    SnCloud.Views.ModalConfirmView = Mn.View.extend({

        template: '#sn-cloud-modal-confirm-template',

        ui: {
            modal: 'div.modal',
            okButton: 'button.btn.ok',
            nokButton: 'button.btn.nok'
        },

        events: {
            'click @ui.nokButton': 'cancel',
            'click @ui.okButton': 'confirm'
        },

        initialize: function (options) {
            this.promise = $.Deferred();
            this.message = options.message;
            $('body').append(this.render().el);
        },

        getPromise: function () {
            return this.promise;
        },

        onRender: function () {
            if (this.message) {
                this.$el.find('div.modal-title').html(this.message);
            }
        },

        confirm: function () {
            this.promise.resolve();
            this.destroy();
        },

        cancel: function () {
            this.promise.reject();
            this.destroy();
        }
    });

}(SnCloud.config);
