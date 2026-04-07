import template from "./ProductFinderStepMultiselect.html";

export default {
    template,
    data() {
        return {
            selectedPropertyGroupOptionIds: [],
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
    computed: {},
    methods: {
        onNextStepClicked() {
            this.$emit('next-step', this.selectedPropertyGroupOptionIds);
        },
        onPreviousStepClicked() {
            this.$emit('previous-step');
        },
        initializeSelections() {
            if (this.selectedOptions[this.stepNumber]) {
                this.selectedPropertyGroupOptionIds = this.selectedOptions[this.stepNumber];
            }
        },
        toggleOption(optionId) {
            if (this.selectedPropertyGroupOptionIds.includes(optionId)) {
                this.selectedPropertyGroupOptionIds = this.selectedPropertyGroupOptionIds.filter(id => id !== optionId);
            } else {
                this.selectedPropertyGroupOptionIds.push(optionId);
            }
        },
        getOptionClasses(optionId) {
            if (this.selectedPropertyGroupOptionIds.includes(optionId)) {
                return {'btn-primary': true};
            }
            return {'btn-secondary': true};
        }

    },
    mounted() {
        this.initializeSelections();
    }
}

