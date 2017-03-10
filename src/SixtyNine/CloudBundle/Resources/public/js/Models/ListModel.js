/*global SnCloud, Backbone */
void function (config) {

    'use strict';

    SnCloud.Models.ListModel = Backbone.Model.extend({

    });

    SnCloud.Models.ListCollection = Backbone.Collection.extend({
        model: SnCloud.Models.ListModel,
        url: config.routes.wordLists
    });

}(SnCloud.config);
