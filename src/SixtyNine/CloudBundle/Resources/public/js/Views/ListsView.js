/*global SnCloud, Backbone */
void function (config) {

    'use strict';

    SnCloud.Views.ListView = Mn.View.extend({

        tagName: 'tr',
        template: '#sn-cloud-word-list-item-template',

        ui: {
            viewWordsButton: 'button[data-role="view-words"]',
            removeListButton: 'button[data-role="remove-list"]'
        },

        events: {
            'click @ui.viewWordsButton': 'viewWords',
            'click @ui.removeListButton': 'removeList'
        },

        viewWords: function (e) {
            SnCloud.showSpinner();

            location.href = Routing.generate('sn_words_view', {id: this.model.get('id')});
        },

        removeList: function (e) {
            if (SnCloud.fn.confirm(config.translations.areYouSure)) {
                this.model.destroy({wait: true});
            }
        }
    });

    SnCloud.Views.ListsView = Mn.CollectionView.extend({
        el: 'table.words-list tbody',
        template: false,
        childView: SnCloud.Views.ListView
    });

}(SnCloud.config);
