import template from './sw-cms-el-faq-select-element.html.twig';
import './sw-cms-el-faq-select-element.scss';

const { Component, Mixin, Context } = Shopware;
const Criteria = Shopware.Data.Criteria;

Shopware.Component.register('sw-cms-el-faq-select-element', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('cms-element')
    ],

    created() {
        this.createdComponent();
    },

    data() {
        return {
            faq: null,
            question: 'Liste ausgewählter Fragen',
            answer: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque faucibus maximus velit, dictum mollis erat finibus quis. Ut dictum ornare dolor, sed mattis tellus gravida vel.',
            categoryName: 'Placeholder Category'
        }
    },

    computed: {

        repository() {
            return this.repositoryFactory.create('ww_faq');
        },

        selectedFaqEntry() {
            return this.element.config.faqEntry.value;
        }
    },

    methods: {
        createdComponent() {
            this.initElementConfig('faq-select-element');
            this.initElementData('faq-select-element');
        }
    },
});