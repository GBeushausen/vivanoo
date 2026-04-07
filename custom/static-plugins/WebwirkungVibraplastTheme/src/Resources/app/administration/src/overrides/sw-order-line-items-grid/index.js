import template from './sw-order-line-items-grid.html.twig';

const { Component } = Shopware;

Component.override('sw-order-line-items-grid', {
    template,

    computed: {
        configuratorOptions() {
            let options = {};
            this.orderLineItems.forEach((lineItem) => {
                if (lineItem.payload.configuratorOptions) {
                    options = {...options, ...lineItem.payload.configuratorOptions};
                }

                if (lineItem.payload.variantPropertyOptionData) {
                    options = {
                        ...options, ...{
                            [lineItem.payload.variantPropertyOptionData.groupId]: {
                                name: lineItem.payload.variantPropertyOptionData.group.name,
                                value: lineItem.payload.variantPropertyOptionData.name,
                            },
                        }
                    };
                }
            })

            return options;
        },

        configuratorOptionsList() {
            let options = {};
            Object.keys(this.configuratorOptions).forEach((id) => {
                options[id] = this.configuratorOptions[id].name;
            })
            return options;
        },

        configuratorOptionsColumnsSlots() {
            return Object.keys(this.configuratorOptionsList).map((id) => {
                return {
                    slotName: 'column-configuratorOptions-' + id,
                    optionId: id,
                }
            });
        },

        getLineItemColumns() {
            const columns = this.$super('getLineItemColumns');

            Object.keys(this.configuratorOptionsList).forEach((optionId) => {
                columns.push({
                    property: 'configuratorOptions-' + optionId,
                    label: this.configuratorOptionsList[optionId],
                    allowResize: true,
                })
            })

            return columns;
        },
    },
})