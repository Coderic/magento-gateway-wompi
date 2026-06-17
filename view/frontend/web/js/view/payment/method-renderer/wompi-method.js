define([
    'Magento_Checkout/js/view/payment/default',
    'Magento_Checkout/js/action/redirect-on-success',
    'mage/url'
], function (Component, redirectOnSuccessAction, url) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Wompi_Payment/payment/wompi',
            redirectAfterPlaceOrder: true
        },

        afterPlaceOrder: function () {
            redirectOnSuccessAction.redirectUrl = url.build('wompi/checkout/start');
            this._super();
        },

        getRedirectMessage: function () {
            return window.checkoutConfig.payment.wompi_payment.redirectMessage;
        },

        isSandbox: function () {
            return window.checkoutConfig.payment.wompi_payment.environment === 'sandbox';
        }
    });
});
