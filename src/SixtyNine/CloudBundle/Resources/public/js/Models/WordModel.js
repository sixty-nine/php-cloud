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
            this.listenTo(this.collection, 'reset', this.render);
        },
        sortWords: function (sortBy, order) {

            var self = this;
            var deferred = $.Deferred();

            $.post({
                url: Routing.generate('cloud_api_sort_words', {id: self.listId}),
                data: { sortBy: sortBy, order: order }
            }).then(function (data) {
                self.fetch().then(function () {
                    deferred.resolve(self);
                });
            });

            return deferred;
        }

    });

}(SnCloud.config);
