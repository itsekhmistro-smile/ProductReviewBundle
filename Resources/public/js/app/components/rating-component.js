define(function(require) {
    'use strict';

    const BaseComponent = require('oroui/js/app/components/base/component');
    const $ = require('jquery');
    const _ = require('underscore');

    const  ProductRaiting = BaseComponent.extend({

        options: {
            readonly: true,
            max_value: 5
        },

        inputOptions: {
            max_value: 5,
            step_size: 1
        },

        /**
         * @property {jQuery.Element}
         */

        rateInit: function() {
            $('.star-review').rate(this.options);
            $('.star-review--input').rate(this.inputOptions);
        },

        setRaitingInput: function() {
            $(".star-review--input").on("change", function(){
                $('.form-row--rating .custom-radio__control')[$(".star-review--input").rate('getValue')].checked = true
            });
        },
        /**
         * {@inheritdoc}
         */
        constructor: function ProductRaiting(options) {
            ProductRaiting.__super__.constructor.call(this, options);
        },

        /**
         * {@inheritdoc}
         */
        initialize: function(options) {
            ProductRaiting.__super__.initialize.call(this, options);

            this.rateInit()
            this.setRaitingInput();

            this.options = _.defaults(options || {}, this.options);
        }


    });

    return ProductRaiting;
});
