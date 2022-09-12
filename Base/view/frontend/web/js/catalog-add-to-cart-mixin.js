define([
    'jquery',
    'mage/translate',
    'jquery/ui'
],
function ($, $t) {
    'use strict';

    return function (target) {
        $.widget('mage.catalogAddToCart', target, {
            options: {
                addToCartButtonTextAdded: $t('Add to Bag'),
                addToCartButtonTextWhileAdding: $t('Adding to Cart ...'),
                addToCartButtonTextDefault: $t('Add to Bag')
            }
        });

        return $.mage.catalogAddToCart;
    };
});

