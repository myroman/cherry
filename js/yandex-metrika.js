(function($){

    window.Cherry = window.Cherry || {};

    window.Cherry.YandexMetrika = function() {
        var productName = 'ОГНЕТУШИТЕЛЬ ВИШНЯ';
        var price = 3940.00;
        return {
            addToCart: function(quantity) {
                dataLayer.push({
                    "ecommerce": {
                        "add": {
                            "products": [
                                {
                                    "name" : productName,
                                    "price": price,
                                    "quantity": quantity
                                }
                            ]
                        }
                    }
                });
            },

            removeFromCart: function(quantity) {
                dataLayer.push({
                    "ecommerce": {
                        "remove": {
                            "products": [
                                {
                                    "name" : productName,
                                    "price": price,
                                    "quantity": quantity
                                }
                            ]
                        }
                    }
                });
            },

            view: function() {
                dataLayer.push({
                    "ecommerce": {
                      "currencyCode": "RUB",
                        "detail": {
                            "products": [
                                {
                                    "name" : productName,
                                    "price": price
                                }
                            ]
                        }
                    }
                });
            },

            purchase: function(quantity) {
                dataLayer.push({
                    "ecommerce": {
                        "purchase": {
                            "actionField": {
                                "id" : "TRX987"//TODO
                            },
                            "products": [
                                {
                                    "name" : productName,
                                    "price": price,
                                    "quantity": 3
                                }
                            ]
                        }
                    }
                });
            }
        }
    }();

})(jQuery);