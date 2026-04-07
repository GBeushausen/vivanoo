import template from './sw-cms-el-step-by-step-element.html.twig';
import './sw-cms-el-step-by-step-element.scss';

const {Component} = Shopware;

Component.register('sw-cms-el-step-by-step-element', {
    template,

    mixins: [
        'cms-element'
    ],
    data() {
        return {
            editable: true,
            demoValue: 'test',
            textPreviewValue: '',
            sliderPos: 0
        };
    },
    computed: {
        textPreview: {
            get() {
                return this.element.textPreviewValue;
            },
            set(value) {
                this.element.textPreviewValue = value;
            }
        }
    },
    created() {
        this.createdComponent();
    },
    mounted() {
        this.updateTextPreview(0);
    },
    methods: {
        createdComponent() {
            this.initElementConfig('step-by-step-element');
        },
        onBlur(content) {
            this.emitChanges(content);
        },

        onInput(content) {
            this.emitChanges(content);
        },

        emitChanges(content) {
            if (content !== this.element.config.stepByStepItems.value[this.sliderPos].value) {
                this.element.config.stepByStepItems.value[this.sliderPos].value = content;
                this.$emit('element-update', this.element);
            }
        },
        setSliderArrowItem(direction = 1) {
            if (this.element.config.stepByStepItems.value.length < 2) {
                return;
            }
            this.sliderPos += direction;
            if (this.sliderPos < 0) {
                this.sliderPos = 0;
            }
            if (this.sliderPos > this.element.config.stepByStepItems.value.length - 1) {
                this.sliderPos = this.element.config.stepByStepItems.value.length - 1;
            }
            this.updateTextPreview(this.sliderPos);
        },
        updateTextPreview(index) {
            if (this.element.config.stepByStepItems.value[index] && this.element.config.stepByStepItems.value[index].value) {
                this.element.textPreview = this.element.config.stepByStepItems.value[index].value;
            }
        },
    },
});
