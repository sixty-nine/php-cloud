/*global SnCloud, Backbone */
void function (config) {

    'use strict';

    SnCloud.Models.CloudModel = Backbone.Model.extend({

    });

    SnCloud.Models.CloudCollection = Backbone.Collection.extend({
        model: SnCloud.Models.CloudModel,
        url: Routing.generate('cloud_api_get_clouds')
    });

}(SnCloud.config);
