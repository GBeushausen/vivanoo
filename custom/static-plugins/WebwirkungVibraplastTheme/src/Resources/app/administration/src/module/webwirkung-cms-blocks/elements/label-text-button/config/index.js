import template from './sw-cms-el-config-label-text-button.html.twig';
import './sw-cms-el-config-label-text-button.scss';

const { Component, Mixin, Context, Utils } = Shopware;

Component.register('sw-cms-el-config-label-text-button', {
    template,

    mixins: [
        'cms-element'
    ],
    inject: ['repositoryFactory'],
    
    computed: {
        alignment: {
            get() {
                return this.element.config.alignment.value;
            },
            
            set(value) {
                this.element.config.alignment.value = value;
            }
        },
        label: {
            get() {
                return this.element.config.label.value;
            },
            
            set(value) {
                this.element.config.label.value = value;
            }
        },
        longText: {
            get() {
                return this.element.config.longText.value;
            },
            
            set(value) {
                this.element.config.longText.value = value;
            }
        }
    },
    created() {
        this.createdComponent();
    },
    
    methods: {
        createdComponent() {
            this.initElementConfig('label-text-button');
        },
        
        onElementUpdateContent(value) {
            // this.element.config.headerItem.value.content = value;
            this.$emit('element-update', this.element);
        },
        onElementUpdateActive(value) {
            // this.element.config.headerItem.value.active = value;
            this.$emit('element-update', this.element);
        }
        
    }
});
