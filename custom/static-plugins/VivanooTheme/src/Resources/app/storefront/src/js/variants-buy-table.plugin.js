import Plugin from 'src/plugin-system/plugin.class'

export default class VariantsBuyTablePlugin extends Plugin {
    init() {
        this.productTitle = document.querySelector('.product-detail-headline')
        this.variantsTableSelect = this.el.querySelector('#variantsTableSelect');
        this.configuratorBtn = this.el.querySelector('.product-configurator-btn');

        this.variantsTableOptionId = (this.variantsTableSelect && this.variantsTableSelect.value)
            ? this.variantsTableSelect.value
            : null;

        this.quantityFields = this.el.querySelectorAll('.buy-widget input[type="number"]');

        this.initRangeSliders();

        if (this.variantsTableSelect) this.initCloneOfSelectbox();
        this.initEventListeners();

        if (this.variantsTableSelect && this.variantsTableSelect.value === '') {
            this.variantsTableOptionId = null;
        }

        this.filterTable();
    }

    initRangeSliders() {
        this.rangeSliders = this.el.querySelectorAll('.range-slider');
        this.rangeSliderNames = ['length', 'width', 'height'];
        this.rangeSliders.forEach((slider) => {
            const dimensionAttribute = slider.getAttribute('data-dimension');
            if (!dimensionAttribute) return;

            this.rangeSliderNames.push(dimensionAttribute);
        });

        this.rangeSliderNames.forEach((dimensionLowerCase) => {
            const dimension = dimensionLowerCase.charAt(0).toUpperCase() + dimensionLowerCase.slice(1)

            const fromInput = this.el.querySelector(`.slider-${dimensionLowerCase}-input-from`);
            const toInput = this.el.querySelector(`.slider-${dimensionLowerCase}-input-to`);
            const fromSlider = this.el.querySelector(`.slider-${dimensionLowerCase}-from`);
            const toSlider = this.el.querySelector(`.slider-${dimensionLowerCase}-to`);

            if (fromInput && toInput && fromSlider && toSlider) {
                this[`is${dimension}SliderEnabled`] = true;
                this[`from${dimension}Input`] = fromInput;
                this[`to${dimension}Input`] = toInput;
                this[`from${dimension}Slider`] = fromSlider;
                this[`to${dimension}Slider`] = toSlider;
            } else {
                this[`is${dimension}SliderEnabled`] = null;
                this[`from${dimension}Input`] = null;
                this[`to${dimension}Input`] = null;
                this[`from${dimension}Slider`] = null;
                this[`to${dimension}Slider`] = false;
            }

            this[`min${dimension}`] = 0;
            this[`max${dimension}`] = this[`to${dimension}Input`] ? parseFloat(this[`to${dimension}Input`].max) : 0;

            if (this[`is${dimension}SliderEnabled`]) {
                const maxValue = this[`max${dimension}`];
                //Automatically set the step value based on the max value's decimal places
                const step = this.getStepValueFromDecimalPlaces(maxValue);

                this[`from${dimension}Slider`].step = step;
                this[`to${dimension}Slider`].step = step;
                this[`from${dimension}Input`].step = step;
                this[`to${dimension}Input`].step = step;

                this[`to${dimension}Slider`].value = maxValue;
                this[`to${dimension}Input`].value = maxValue;
            }
        });
    }

    getStepValueFromDecimalPlaces(value) {
        const valueStr = value.toString();
        const decimalIndex = valueStr.indexOf('.');

        if (decimalIndex === -1) {
            return 1;
        }

        const decimalPlaces = valueStr.length - decimalIndex - 1;
        return Math.pow(10, -decimalPlaces);
    }

    initCloneOfSelectbox() {
        const parentVariantSelect = this.variantsTableSelect.parentNode;
        const clonedSelect = parentVariantSelect.cloneNode(true);
        const clonedSelectBox = clonedSelect.querySelector('select');
        clonedSelectBox.id = 'variantsTableSelectCloned';

        // create a new div and append the clonedSelect to it
        const wrapperDiv = document.createElement('div');
        wrapperDiv.classList.add('product-detail-main-variant-selection');
        wrapperDiv.appendChild(clonedSelect);

        this.productTitle.insertAdjacentElement('afterend', wrapperDiv);

        this.variantsTableSelectCloned = document.getElementById('variantsTableSelectCloned');

        this.variantsTableSelectCloned.value = this.variantsTableSelect.value ?? '';

        this.variantsTableSelectCloned.addEventListener('change', {
            handleEvent: function (event) {
                this.variantsTableSelect.value = this.variantsTableSelectCloned.value;
                this.onVariantsTablePropertySelect.bind(this)(event);
            }.bind(this),
        });
    }

    initEventListeners() {
        if (this.variantsTableSelect) {
            this.variantsTableSelect.addEventListener('change', {
                handleEvent: function (event) {
                    if (this.variantsTableSelectCloned) {
                        this.variantsTableSelectCloned.value = this.variantsTableSelect.value;
                    }
                    this.onVariantsTablePropertySelect.bind(this)(event);
                }.bind(this),
            });
        }

        if (this.configuratorBtn) {
            this.configuratorBtn.addEventListener('click', () => {
                const configuratorSection = document.querySelector('.variant-configurator');
                if (configuratorSection) {
                    configuratorSection.classList.remove('d-none');
                    this.el.classList.add('d-none');
                }
            });
        }

        this.rangeSliderNames.forEach((dimensionLowerCase) => {
            const dimension = dimensionLowerCase.charAt(0).toUpperCase() + dimensionLowerCase.slice(1)

            if (this[`is${dimension}SliderEnabled`]) {
                this[`from${dimension}Input`].addEventListener('change', this.onFromDimensionChange.bind(this));
                this[`to${dimension}Input`].addEventListener('change', this.onToDimensionChange.bind(this));
                this[`from${dimension}Slider`].addEventListener('input', this.onFromDimensionChange.bind(this));
                this[`to${dimension}Slider`].addEventListener('input', this.onToDimensionChange.bind(this));
            }
        });

        this.quantityFields.forEach((quantityField) => {
            quantityField.addEventListener('change', this.calculateTotalQuantityUnit.bind(this));
            quantityField.addEventListener('input', this.calculateTotalQuantityUnit.bind(this));
        });

    }

    onVariantsTablePropertySelect(event) {
        const val = event.target.value;
        this.variantsTableOptionId = val && val.length ? val : null;
        this.filterTable();
    }

    onFromDimensionChange(event) {
        const dimensionAttribute = event.target.getAttribute('data-dimension');
        if (!dimensionAttribute) return;
        const dimension = dimensionAttribute.charAt(0).toUpperCase() + dimensionAttribute.slice(1);
        let minVar = this['min' + dimension];
        let maxVar = this['max' + dimension];

        this['min' + dimension] = parseFloat(event.target.value);
        this['to' + dimension + 'Input'].min = this['min' + dimension];
        this['to' + dimension + 'Slider'].min = this['min' + dimension];
        this['from' + dimension + 'Input'].value = this['min' + dimension];

        if (maxVar < minVar) {
            this['max' + dimension] = minVar;
            this['to' + dimension + 'Input'].value = maxVar;
        }
        this.updateSliders();
        this.filterTable();
    }

    onToDimensionChange(event) {
        const dimensionAttribute = event.target.getAttribute('data-dimension');
        const dimension = dimensionAttribute.charAt(0).toUpperCase() + dimensionAttribute.slice(1);
        let minVar = this['min' + dimension];
        let maxVar = this['max' + dimension];


        this['max' + dimension] = parseFloat(event.target.value);
        this['from' + dimension + 'Input'].max = this['max' + dimension];
        this['from' + dimension + 'Slider'].max = this['max' + dimension];
        this['to' + dimension + 'Input'].value = this['max' + dimension];

        if (minVar > maxVar) {
            this['min' + dimension] = maxVar;
            this['from' + dimension + 'Input'].value = minVar;
        }

        this.updateSliders();
        this.filterTable();
    }

    updateSliders() {
        this.rangeSliderNames.forEach((dimensionLowerCase) => {
            const dimension = dimensionLowerCase.charAt(0).toUpperCase() + dimensionLowerCase.slice(1)

            if (this[`is${dimension}SliderEnabled`]) {
                this[`from${dimension}Slider`].value = this[`min${dimension}`];
                this[`to${dimension}Slider`].value = this[`max${dimension}`];
            }
        });
    }

    filterTable() {
        const rows = this.el.querySelectorAll('table tr.variant-row');
        const noVariantsInfo = this.el.querySelector('.product-detail-buy-table-no-variants');

        rows.forEach(row => {
            const variantsTableOptionId = row.getAttribute('data-variant-table-property-option');

            if (this.variantsTableOptionId && variantsTableOptionId !== this.variantsTableOptionId) {
                row.classList.add('d-none');
                return;
            }

            let showRow = true;

            this.rangeSliderNames.forEach((dimensionLowerCase) => {
                const dimension = dimensionLowerCase.charAt(0).toUpperCase() + dimensionLowerCase.slice(1);
                const value = parseFloat(row.getAttribute(`data-variant-${dimensionLowerCase}`));

                if (this[`min${dimension}`] && value < this[`min${dimension}`]) {
                    showRow = false;
                }
                if (this[`max${dimension}`] && value > this[`max${dimension}`]) {
                    showRow = false;
                }
            });

            if (showRow) {
                row.classList.remove('d-none');

                return;
            }

            row.classList.add('d-none');

        });


        if (this.checkAllRowsHidden()) {
            if (noVariantsInfo) noVariantsInfo.classList.remove('d-none')
        } else {
            if (noVariantsInfo) noVariantsInfo.classList.add('d-none')
        }
    }

    checkAllRowsHidden() {
        const rows = this.el.querySelectorAll('table tr.variant-row');
        let allHidden = true;

        for (let i = 0; i < rows.length; i++) {
            if (!rows[i].classList.contains('d-none')) {
                allHidden = false;
                break;
            }
        }

        return allHidden;
    }

    calculateTotalQuantityUnit(event) {
        const {target: {value: quantity, parentElement: {nextElementSibling: variantQuantityWrapper}}} = event;
        if (!variantQuantityWrapper) {
            return;
        }
        const calculatedUnitElement = variantQuantityWrapper.querySelector('.variant-quantity-calculated');
        const calculated = quantity * calculatedUnitElement.getAttribute('data-initial-quantity');
        if (Number.isInteger(calculated)) {
            calculatedUnitElement.innerHTML = Number(calculated);
            return;
        }
        calculatedUnitElement.innerHTML = (calculated).toFixed(2);
    }
}
