import template from "./ProductFinderResult.html";

export default {
    template,
    props: {
        stepLabel: {
            type: String,
            required: true,
        },
        stepQuestion: {
            type: String,
            required: true,
        },
        result: {
            type: String,
            required: true,
        },
        previousStepLabel: {
            type: String,
            required: true,
        },
        resetLabel: {
            type: String,
            required: true,
        },
    },
    methods: {
        onPreviousStepClicked() {
            this.$emit('previous-step');
        },
        onResetClicked() {
            this.$emit('reset');
        },
        initRemoveListeners(){
            const removeButtons = this.$refs.resultContainer.querySelectorAll('.filter-active[data-option-id]');

            removeButtons.forEach((button) => {
                button.addEventListener('click', (event) => {
                    const optionId = event.target.getAttribute('data-option-id');
                    this.$emit('remove-option', optionId);
                });
            });
        }
    },
    mounted() {
        this.initRemoveListeners();
    }
}

