import template from './sw-cms-el-config-step-by-step-element.html.twig';
import './sw-cms-el-config-step-by-step-element.scss';

const { Component, Mixin, Context, Utils } = Shopware;

Component.register('sw-cms-el-config-step-by-step-element', {
    template,

    mixins: [
        'cms-element'
    ],
    inject: ['repositoryFactory'],
    
    computed: {
        mediaRepository() {
            return this.repositoryFactory.create('media');
        },
    },
    
    data() {
        return {
            counter: 1,
        };
    },
    created() {
        this.createdComponent();
    },
    
    mounted() {
    
    },
    
    methods: {
        createdComponent() {
            this.initElementConfig('step-by-step-element');
        },
    
        onClickAddSlide() {
            this.element.config.stepByStepItems.value.push({
                active: true,
                id: Utils.createId(),
                contentType: 'default',
            
                // Content
                title: 'Bestimmen Sie den gewünschten Schallreduktionsindex (Rw)',
                text: '<p>Der Schallreduktionsindex (Rw) ist ein Maß dafür, wie gut ein Material Schall absorbiert. Je höher der Rw-Wert, desto effektiver ist das Material im Blockieren von Schall. Definieren Sie den gewünschten Rw-Wert basierend auf den Schallschutzanforderungen Ihres Projekts.</p>',
            });
        
        },
    }
});
