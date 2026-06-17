define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (Component, rendererList) {
    'use strict';

    rendererList.push({
        type: 'wompi_payment',
        component: 'Wompi_Payment/js/view/payment/method-renderer/wompi-method'
    });

    return Component.extend({});
});
