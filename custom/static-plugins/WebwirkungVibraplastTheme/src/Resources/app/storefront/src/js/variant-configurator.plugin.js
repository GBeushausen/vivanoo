import Plugin from 'src/plugin-system/plugin.class'
import DomAccess from 'src/helper/dom-access.helper'

export default class VariantConfiguratorPlugin extends Plugin {
    init() {
        this.variantTableBtn = DomAccess.querySelector(this.el, '.product-table-btn');
        this.dynamicConfiguratorOptions = DomAccess.querySelectorAll(this.el, '.slider-configurator');
        this.variantPropertySelector = this.el.querySelector('#variantsTableSelect');
        this.basePrice = this.options.basePrice;
        this.purchaseUnit = this.options.purchaseUnit;
        this.baseUnit = this.options.baseUnit;
        this.currency = this.options.currency;
        this.itemRoundingDecimals = this.options.itemRoundingDecimals;
        this.itemRoundingInterval = this.options.itemRoundingInterval;
        this.initEventListeners();
        this.recalculatePrice();
    }

    initEventListeners() {
        
        // check if domain has #variant-configurator in the url
        const toggleSections = (showConfigurator) => {
            const variantTableSection = document.querySelector('.variants-buy-table');
            const variantConfiguratorSection = document.querySelector('.variant-configurator');
            if (!variantTableSection) return;
            
            variantTableSection.classList.toggle('d-none', showConfigurator);
            variantConfiguratorSection.classList.toggle('d-none', !showConfigurator);
        }

        const shouldSwitchToConfigurator = window.location.href.includes('#variantConfiguratorContainerAnchor');
        if (shouldSwitchToConfigurator && !window.sectionSwitched) {
            toggleSections(true);
            window.sectionSwitched = true;
        }

        this.variantTableBtn.addEventListener('click', () => toggleSections(false));

        Array.from(this.dynamicConfiguratorOptions).forEach((optionGroup) => {
            const optionId = optionGroup.getAttribute('data-option-id');
            const slider = DomAccess.querySelector(optionGroup, '.slider-configurator-' + optionId);
            const input = DomAccess.querySelector(optionGroup, '.input-configurator-' + optionId);
            const summary = DomAccess.querySelector(this.el, '.configurator-summary-' + optionId);
            const summaryPreview = DomAccess.querySelector(this.el, '.configurator-summary-preview-' + optionId);
            const optionHiddenInput = DomAccess.querySelector(document, '.configurator-hidden-input-' + optionId);

            if (slider) {
                slider.addEventListener('change', this.onInputChange.bind(this, slider, input, summary, summaryPreview, optionHiddenInput));
            }
            if (input) {
                input.addEventListener('change', this.onInputChange.bind(this, input, slider, summary, summaryPreview, optionHiddenInput));
            }
        });

        if (this.variantPropertySelector) {
            this.variantPropertySelector.addEventListener('change', this.onVariantPropertyChange.bind(this));
        }
    }

    onVariantPropertyChange(event) {
        const selectedOption = event.target.value;
        const hiddenInput = this.el.querySelector('.variantPropertyOptionInput');

        if (!hiddenInput) {
            return;
        }

        hiddenInput.value = selectedOption;
    }

    onInputChange(source, dest, summary, summaryPreview, input) {
        dest.value = source.value;
        summary.innerHTML = source.value;
        summaryPreview.innerHTML = source.value;
        input.value = source.value;
        this.recalculatePrice();
    }

    recalculatePrice() {

        const priceField = this.el.querySelector('.product-detail-price');
        if (!priceField) {
            return;
        }

        let cubicMilimeter = 1;
        const dimensions = Array.from(this.dynamicConfiguratorOptions).map((optionGroup) => {
            const optionId = optionGroup.getAttribute('data-option-id');
            const input = DomAccess.querySelector(optionGroup, '.input-configurator-' + optionId);
            return parseInt(input.value);
        })

        dimensions.forEach((dimension) => {
            cubicMilimeter *= dimension;
        });

        const cubicMeter = this.calculate(dimensions, cubicMilimeter, this.baseUnit);

        const customPrice = this.round((this.basePrice * cubicMeter) / this.purchaseUnit);

        priceField.innerHTML = this.currency + ' ' + customPrice;
    }

    calculate(dimensions, value, unit) {
        if (dimensions.length === 2) {
            switch (unit) {
                case 'mm²':
                    return value;
                case 'cm²':
                    return value / 100;
                case 'dm²':
                    return value / 10000;
                case 'm²':
                    return value / 1000000;
                default:
                    return value / 1000000;
            }
        }

        if (dimensions.length === 3) {
            switch (unit) {
                case 'mm³':
                    return value;
                case 'cm³':
                    return value / 1000;
                case 'dm³':
                    return value / 1000000;
                case 'm³':
                    return value / 1000000000;
                default:
                    return value / 1000000000;
            }
        }

        return value;
    }

    round(value) {
        const rounded = value.toFixed(this.itemRoundingDecimals);
        if (this.itemRoundingDecimals > 2) {
            return rounded;
        }

        const multiplier = 100 / (this.itemRoundingInterval * 100);
        return (rounded * multiplier).toFixed(0) / multiplier;
    }
}