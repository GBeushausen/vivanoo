import template from './sw-order-detail-general.html.twig'

const { Component } = Shopware;

Component.override('sw-order-detail-general', {
    template,

    computed: {
        isMinimumOrderSurcharge() {
            const customFields = this.order.customFields;

            if (
                !customFields.minimumOrderSurcharge
                || !customFields.minimumOrderSurcharge.totalPrice
            ) {
                return false;
            }

            return customFields.minimumOrderSurcharge.totalPrice > 0;
        },
    },

});

