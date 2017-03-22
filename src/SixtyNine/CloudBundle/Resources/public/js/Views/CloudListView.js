/*global SnCloud, Backbone */
void function (config) {

    'use strict';

    SnCloud.Views.CloudView = Mn.View.extend({

        template: '#sn-cloud-item-template',

        ui: {
            viewButton: 'a[data-role="view"]',
            regenButton: 'a[data-role="regenerate"]',
            removeButton: 'a[data-role="remove"]'
        },

        events: {
            'click @ui.viewButton': 'viewCloud',
            'click @ui.regenButton': 'regenerateCloud',
            'click @ui.removeButton': 'removeCloud'
        },

        viewCloud: function () {
            SnCloud.showSpinner();
            location.href = Routing.generate('sn_cloud_view', {id: this.model.get('id')});
        },

        regenerateCloud: function () {
            SnCloud.showSpinner();
            location.href = Routing.generate('sn_cloud_generate', {id: this.model.get('id')});
        },

        removeCloud: function () {
            SnCloud.fn.confirm().then(_.bind(function () {
                SnCloud.showSpinner();
                this.model.destroy({wait: true}).then(function () {
                    SnCloud.hideSpinner();
                });
            }, this));
        }
    });

    SnCloud.Views.CloudsView = Mn.CollectionView.extend({
        el: 'section.clouds',
        template: false,
        childView: SnCloud.Views.CloudView,
        emptyView: Mn.View.extend({
            template: '#sn-cloud-no-item-template'
        })
    });

}(SnCloud.config);
