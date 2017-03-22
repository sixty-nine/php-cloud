/*global SnCloud, Backbone */
void function (config) {

    'use strict';

    SnCloud.Views.ViewCloudView = Mn.View.extend({

        el: 'section.container',

        ui: {
            showBBButton: '[data-role="show-bb"]',
            showPlacerButton: '[data-role="show-placer"]',
            image: '#cloud img'
        },

        initialize: function (options) {
            this.cloudId = options.cloudId;
        },

        events: {
            'click @ui.showBBButton': 'showBB',
            'click @ui.showPlacerButton': 'showPlacer'
        },

        showBB: function (e) {
            this.showCloud();
        },

        showPlacer: function () {
            this.showCloud();
        },

        showCloud: function () {
            var params = {
                'id': this.cloudId,
                'show-bb': this.ui.showBBButton.is(':checked') ? '1' : '0',
                'show-placer': this.ui.showPlacerButton.is(':checked') ? '1' : '0'
            };
            var url = Routing.generate('sn_cloud_render', params);
            this.ui.image.attr('src', url);
        }

    });

}(SnCloud.config);
