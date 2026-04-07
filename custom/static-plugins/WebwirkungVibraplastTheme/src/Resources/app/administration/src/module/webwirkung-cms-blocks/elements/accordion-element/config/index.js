import template from './sw-cms-el-config-accordion-element.html.twig';
import './sw-cms-el-config-accordion-element.scss';

const { Component, Mixin, Context, Utils } = Shopware;

Component.register('sw-cms-el-config-accordion-element', {
    template,

    mixins: [
        'cms-element'
    ],
    computed: {
        accordionTitle: {
            get() {
                return this.element.config.accordionTitle.value;
            },
            
            set(value) {
                this.element.config.accordionTitle.value = value;
            }
        },
        accordionText: {
            get() {
                return this.element.config.accordionText.value;
            },
            
            set(value) {
                this.element.config.accordionText.value = value;
            }
        },
    },
    created() {
        this.createdComponent();
    },
    
    
    methods: {
        createdComponent() {
            this.initElementConfig('accordion-element');
        },
    }
});
