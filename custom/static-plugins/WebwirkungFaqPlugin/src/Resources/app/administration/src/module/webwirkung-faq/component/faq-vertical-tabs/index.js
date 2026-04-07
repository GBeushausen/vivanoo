import template from './faq-vertical-tabs.html.twig';

const { Component } = Shopware;

Component.register('faq-vertical-tabs', {
    template,

    props: {
        defaultItem: {
            type: String,
            default: 'faq'
        }
    },

    methods: {
        onChangeTab(name) {
            this.currentTab = name;
        }
    }
});
