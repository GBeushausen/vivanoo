import template from "./ProductFinder.html";
import ProductFinderStepMarker from "./../ProductFinderStepMarker";
import ProductFinderStep from "./../ProductFinderStep";
import ProductFinderStepMultiselect from "./../ProductFinderStepMultiselect";
import ProductFinderResult from "./../ProductFinderResult";
import HttpClient from 'src/service/http-client.service';

export default {
    template,
    components: {
        ProductFinderStepMarker,
        ProductFinderStep,
        ProductFinderStepMultiselect,
        ProductFinderResult,
    },
    data() {
        return {
            activeStep: 1,
            selectedCategory: 0,
            selectedOptions: {},
            stepQuestion: '',
            step1Question2: '',
            stepsLabels: [],
            stepProperties: [],
            stepSelectionType: 'single',
            httpClient: new HttpClient(),
            isLoading: false,
            result: '',
        }
    },
    props: {
        config: {
            type: Object,
            required: true,
        },
    },
    computed: {
        categories() {
            return this.config.categories;
        },
        preSelectedCategory() {
            return this.config.selectedCategoryId;
        },
        selectCategoryLabel() {
            return this.config.selectCategoryLabel;
        },
        nextStepLabel() {
            return this.config.nextLabel;
        },
        previousStepLabel() {
            return this.config.prevLabel;
        },
        resetLabel() {
            return this.config.resetLabel;
        },
        stepDataUrl() {
            return `${this.config.stepDataUrl}/${this.selectedCategory}`;
        },
        resultDataUrl() {
            return `${this.config.resultDataUrl}/${this.selectedCategory}`;
        },
        stepDataFinalUrl() {
            return `${this.stepDataUrl}/${this.activeStep}`;
        },
        stepDataResultUrl() {
            return `${this.resultDataUrl}?properties=${this.encodedSelectedOptions}`;
        },
        resultHeading() {
            return this.config.resultHeading;
        },

        flattenOptions() {
            let values = [];
            Object.keys(this.selectedOptions).forEach((key) => {
                values = values.concat(this.selectedOptions[key]);
            });
            return values;
        },
        encodedSelectedOptions() {
            return this.flattenOptions.join('|');
        }
    },
    methods: {
        preselectCategory() {
            if (!this.preSelectedCategory) {
                return;
            }
            const matchingCategory = this.categories.find(category => category.id === this.preSelectedCategory);
            if (!matchingCategory) {
                return;
            }
            this.selectedCategory = matchingCategory.id;
        },
        onChangeCategory() {
            this.activeStep = 1;
            this.selectedOptions = {};
        },

        onNextStep(propertyGroupOptions) {
            this.selectedOptions[this.activeStep] = propertyGroupOptions;
            this.activeStep++;
        },

        onPreviousStep() {
            if (this.selectedOptions[this.activeStep]) {
                delete this.selectedOptions[this.activeStep];
            }
            this.activeStep--;
        },
        onReset() {
            this.activeStep = 1;
            this.selectedOptions = {};
        },
        getStepData() {
            this.isLoading = true;
            this.httpClient.get(this.stepDataFinalUrl, (response) => {
                const {
                    stepLabel,
                    step1Question2,
                    stepProperties,
                    stepSelectionType,
                    stepsLabels
                } = JSON.parse(response);
                this.stepQuestion = stepLabel;
                this.step1Question2 = step1Question2;
                this.stepProperties = stepProperties;
                this.stepSelectionType = stepSelectionType;
                this.stepsLabels = stepsLabels;
                this.isLoading = false;
            });
        },
        getResult() {
            this.isLoading = true;
            this.httpClient.get(this.stepDataResultUrl, (response) => {
                this.result = response;
                this.isLoading = false;
            });
        },
        onRemoveOption(optionId) {
            const keys = Object.keys(this.selectedOptions);
            keys.forEach((key) => {
                if (this.selectedOptions[key].includes(optionId)) {
                    this.selectedOptions[key] = this.selectedOptions[key].filter((option) => option !== optionId);
                }
                if (this.selectedOptions[key].length === 0) {
                    delete this.selectedOptions[key];
                }
            })

            this.getResult();

        }
    },
    mounted() {
        this.preselectCategory();
    },
    watch: {
        stepDataFinalUrl() {
            if (this.selectedCategory && this.activeStep < 4) {
                this.getStepData();
            }
        },
        activeStep() {
            if (this.activeStep === 4) {
                this.getResult();
            }
        }
    },
}

