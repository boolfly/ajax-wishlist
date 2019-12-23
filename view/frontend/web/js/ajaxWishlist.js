/**
 * Ajax Wishlist
 *
 * @copyright Copyright © Boolfly. All rights reserved.
 * @author    info@boolfly.com
 * @project   Ajax Wishlist
 */
define([
    'jquery',
    'underscore',
    'uiComponent',
    'Magento_Customer/js/customer-data'
], function ($, _, Component, customerData) {
    'use strict';

    return Component.extend({
        productIds: [],

        /** @inheritDoc */
        initialize: function () {
            this._super();
            this.initWishlistProduct();
        },

        /**
         * Init Wish list Product
         *
         * @inheritDoc
         */
        initWishlistProduct: function () {
            this.wishlist = customerData.get('wishlist');
            this.wishlist.subscribe(function (value) {
                this.productIds(value.wishlist_ids)
            }, this);
            this.observe('productIds', []);
            this.productIds(this.wishlist().wishlist_ids);
        },

        /** @inheritDoc */
        beforeSendAjaxEvent: function (ajaxWishlist, event) {
            $(event.currentTarget).addClass('loading');
        },

        /** @inheritDoc */
        completeAjaxEvent: function (ajaxWishlist, event) {
            $(event.currentTarget).removeClass('loading');
        },

        /**
         * Check product added
         *
         * @inheritDoc
         */
        added: function (element) {
            return this.productIds.indexOf($(element).data('product-id').toString()) > -1;
        }
    });
});