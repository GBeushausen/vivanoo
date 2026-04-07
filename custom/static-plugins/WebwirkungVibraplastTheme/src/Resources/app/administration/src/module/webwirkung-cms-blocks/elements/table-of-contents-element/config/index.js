import template from './sw-cms-el-config-table-of-contents-element.html.twig';

const { Component, Mixin } = Shopware;
const { Criteria, EntityCollection } = Shopware.Data;

Component.register('sw-cms-el-config-table-of-contents-element', {
    template,

    mixins: [
        'cms-element'
    ],
    inject: ['repositoryFactory'],
    
    computed: {

        tableLabel: {
            get() {
                return this.element.config.tableLabel.value;
            },
            
            set(value) {
                this.element.config.tableLabel.value = value;
            }
        },
        titleType: {
            get() {
                return this.element.config.titleType.value;
            },
            
            set(value) {
                this.element.config.titleType.value = value;
            }
        },
     
    },
    
    created() {
        this.createdComponent();
    },
    
    methods: {
        createdComponent() {
            this.initElementConfig('table-of-contents-element');
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
