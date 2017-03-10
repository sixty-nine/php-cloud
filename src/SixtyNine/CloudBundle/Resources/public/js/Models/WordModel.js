/*global SnCloud, Backbone */
void function (config) {

    'use strict';

    SnCloud.Models.WordModel = Backbone.Model.extend({

    });

    SnCloud.Models.WordCollection = Backbone.Collection.extend({
        model: SnCloud.Models.WordModel,
        url: function () {
            return Routing.generate('cloud_api_get_words', {id: this.listId});
        },
        initialize: function (models, options) {
            this.listId = options.listId;
        }
    });

}(SnCloud.config);
