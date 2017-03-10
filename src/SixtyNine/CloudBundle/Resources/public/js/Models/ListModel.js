/*global SnCloud, Backbone */
void function (config) {

    'use strict';

    SnCloud.Models.ListModel = Backbone.Model.extend({

    });

    SnCloud.Models.ListCollection = Backbone.Collection.extend({
        model: SnCloud.Models.ListModel,
        url: Routing.generate('cloud_api_get_lists')
    });

}(SnCloud.config);
