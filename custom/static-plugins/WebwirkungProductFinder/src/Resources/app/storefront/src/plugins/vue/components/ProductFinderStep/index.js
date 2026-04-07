import template from "./ProductFinderStep.html";

export default {
    template,
    data() {
        return {
            selectedPropertyGroupId: '',
            selectedPropertyGroupOptionId: '',
        }
    },
    props: {
        stepLabel: {
            type: String,
            required: true,
        },
        stepQuestion: {
            type: String,
            required: true,
        },
        step1Question2: {
            type: String,
            required: true,
        },
        stepProperties: {
            type: Array,
            required: true,
        },
        selectedOptions: {
            type: Object,
            required: true,
        },
        stepNumber: {
            type: Number,
            required: true,
        },
        nextStepLabel:{
            type: String,
            required: true,
        },
        previousStepLabel: {
            type: String,
            required: true,
        },
    },
    watch: {
        selectedPropertyGroupId() {
            this.selectedPropertyGroupOptionId = '';
        }
    },
    computed: {
        selectedPropertyOptions() {
            if (!this.selectedPropertyGroupId) {
                return [];
            }

            return this.selectedPropertyGroup ? this.selectedPropertyGroup.options : [];
        },
        selectedPropertyGroup() {
            return this.stepProperties.find(property => property.id === this.selectedPropertyGroupId);
        }
    },
    methods: {
        onNextStepClicked() {
            this.$emit('next-step', [this.selectedPropertyGroupOptionId]);
        },
        onPreviousStepClicked() {
            this.$emit('previous-step');
        },
        initializeSelections() {
            if (this.selectedOptions[this.stepNumber]) {
                const selectedOptionId = this.selectedOptions[this.stepNumber][0];
                const propertyGroup = this.stepProperties.find(property =>
                    !!property.options.find(option => option.id === selectedOptionId)
                );

                if (propertyGroup) {
                    this.selectedPropertyGroupId = propertyGroup.id;
                    this.$nextTick(() => {
                        this.selectedPropertyGroupOptionId = selectedOptionId;
                    });
                }
            }
        }
    },
    mounted() {
        this.initializeSelections();
    }
}

