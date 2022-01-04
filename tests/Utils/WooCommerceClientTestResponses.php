<?php

namespace Tests\Utils;

class WooCommerceClientTestResponses {

    public static function data() : array {
        return [
            'customers' => self::customerData(),
            'coupons' => self::couponData(),
            'orders' => self::orderData(),
            'products' => self::productData()
        ];
    }

    public static function customerData(): array {
        return json_decode('[
            {
              "id": 26,
              "date_created": "2017-03-21T16:11:14",
              "date_created_gmt": "2017-03-21T19:11:14",
              "date_modified": "2017-03-21T16:11:16",
              "date_modified_gmt": "2017-03-21T19:11:16",
              "email": "joao.silva@example.com",
              "first_name": "João",
              "last_name": "Silva",
              "role": "customer",
              "username": "joao.silva",
              "billing": {
                "first_name": "João",
                "last_name": "Silva",
                "company": "",
                "address_1": "Av. Brasil, 432",
                "address_2": "",
                "city": "Rio de Janeiro",
                "state": "RJ",
                "postcode": "12345-000",
                "country": "BR",
                "email": "joao.silva@example.com",
                "phone": "(55) 5555-5555"
              },
              "shipping": {
                "first_name": "João",
                "last_name": "Silva",
                "company": "",
                "address_1": "Av. Brasil, 432",
                "address_2": "",
                "city": "Rio de Janeiro",
                "state": "RJ",
                "postcode": "12345-000",
                "country": "BR"
              },
              "is_paying_customer": false,
              "avatar_url": "https://secure.gravatar.com/avatar/be7b5febff88a2d947c3289e90cdf017?s=96",
              "meta_data": [],
              "_links": {
                "self": [
                  {
                    "href": "https://example.com/wp-json/wc/v3/customers/26"
                  }
                ],
                "collection": [
                  {
                    "href": "https://example.com/wp-json/wc/v3/customers"
                  }
                ]
              }
            },
            {
              "id": 25,
              "date_created": "2017-03-21T16:09:28",
              "date_created_gmt": "2017-03-21T19:09:28",
              "date_modified": "2017-03-21T16:09:30",
              "date_modified_gmt": "2017-03-21T19:09:30",
              "email": "john.doe@example.com",
              "first_name": "John",
              "last_name": "Doe",
              "role": "customer",
              "username": "john.doe",
              "billing": {
                "first_name": "John",
                "last_name": "Doe",
                "company": "",
                "address_1": "969 Market",
                "address_2": "",
                "city": "San Francisco",
                "state": "CA",
                "postcode": "94103",
                "country": "US",
                "email": "john.doe@example.com",
                "phone": "(555) 555-5555"
              },
              "shipping": {
                "first_name": "John",
                "last_name": "Doe",
                "company": "",
                "address_1": "969 Market",
                "address_2": "",
                "city": "San Francisco",
                "state": "CA",
                "postcode": "94103",
                "country": "US"
              },
              "is_paying_customer": false,
              "avatar_url": "https://secure.gravatar.com/avatar/8eb1b522f60d11fa897de1dc6351b7e8?s=96",
              "meta_data": [],
              "_links": {
                "self": [
                  {
                    "href": "https://example.com/wp-json/wc/v3/customers/25"
                  }
                ],
                "collection": [
                  {
                    "href": "https://example.com/wp-json/wc/v3/customers"
                  }
                ]
              }
            }
        ]', true);
    }
    
    public static function couponData(): array {
      return json_decode('[
        {
          "id": 720,
          "code": "free shipping",
          "amount": "0.00",
          "date_created": "2017-03-21T15:25:02",
          "date_created_gmt": "2017-03-21T18:25:02",
          "date_modified": "2017-03-21T15:25:02",
          "date_modified_gmt": "2017-03-21T18:25:02",
          "discount_type": "fixed_cart",
          "description": "",
          "date_expires": null,
          "date_expires_gmt": null,
          "usage_count": 0,
          "individual_use": true,
          "product_ids": [],
          "excluded_product_ids": [],
          "usage_limit": null,
          "usage_limit_per_user": null,
          "limit_usage_to_x_items": null,
          "free_shipping": true,
          "product_categories": [],
          "excluded_product_categories": [],
          "exclude_sale_items": false,
          "minimum_amount": "0.00",
          "maximum_amount": "0.00",
          "email_restrictions": [],
          "used_by": [],
          "meta_data": [],
          "_links": {
            "self": [
              {
                "href": "https://example.com/wp-json/wc/v3/coupons/720"
              }
            ],
            "collection": [
              {
                "href": "https://example.com/wp-json/wc/v3/coupons"
              }
            ]
          }
        },
        {
          "id": 719,
          "code": "10off",
          "amount": "10.00",
          "date_created": "2017-03-21T15:23:00",
          "date_created_gmt": "2017-03-21T18:23:00",
          "date_modified": "2017-03-21T15:23:00",
          "date_modified_gmt": "2017-03-21T18:23:00",
          "discount_type": "percent",
          "description": "",
          "date_expires": null,
          "date_expires_gmt": null,
          "usage_count": 0,
          "individual_use": true,
          "product_ids": [],
          "excluded_product_ids": [],
          "usage_limit": null,
          "usage_limit_per_user": null,
          "limit_usage_to_x_items": null,
          "free_shipping": false,
          "product_categories": [],
          "excluded_product_categories": [],
          "exclude_sale_items": true,
          "minimum_amount": "100.00",
          "maximum_amount": "0.00",
          "email_restrictions": [],
          "used_by": [],
          "meta_data": [],
          "_links": {
            "self": [
              {
                "href": "https://example.com/wp-json/wc/v3/coupons/719"
              }
            ],
            "collection": [
              {
                "href": "https://example.com/wp-json/wc/v3/coupons"
              }
            ]
          }
        }
      ]', true);
    }

    public static function orderData() : array {
      return json_decode('[
        {
          "id": 727,
          "parent_id": 0,
          "number": "727",
          "order_key": "wc_order_58d2d042d1d",
          "created_via": "rest-api",
          "version": "3.0.0",
          "status": "processing",
          "currency": "USD",
          "date_created": "2017-03-22T16:28:02",
          "date_created_gmt": "2017-03-22T19:28:02",
          "date_modified": "2017-03-22T16:28:08",
          "date_modified_gmt": "2017-03-22T19:28:08",
          "discount_total": "0.00",
          "discount_tax": "0.00",
          "shipping_total": "10.00",
          "shipping_tax": "0.00",
          "cart_tax": "1.35",
          "total": "29.35",
          "total_tax": "1.35",
          "prices_include_tax": false,
          "customer_id": 0,
          "customer_ip_address": "",
          "customer_user_agent": "",
          "customer_note": "",
          "billing": {
            "first_name": "John",
            "last_name": "Doe",
            "company": "",
            "address_1": "969 Market",
            "address_2": "",
            "city": "San Francisco",
            "state": "CA",
            "postcode": "94103",
            "country": "US",
            "email": "john.doe@example.com",
            "phone": "(555) 555-5555"
          },
          "shipping": {
            "first_name": "John",
            "last_name": "Doe",
            "company": "",
            "address_1": "969 Market",
            "address_2": "",
            "city": "San Francisco",
            "state": "CA",
            "postcode": "94103",
            "country": "US"
          },
          "payment_method": "bacs",
          "payment_method_title": "Direct Bank Transfer",
          "transaction_id": "",
          "date_paid": "2017-03-22T16:28:08",
          "date_paid_gmt": "2017-03-22T19:28:08",
          "date_completed": null,
          "date_completed_gmt": null,
          "cart_hash": "",
          "meta_data": [
            {
              "id": 13106,
              "key": "_download_permissions_granted",
              "value": "yes"
            },
            {
              "id": 13109,
              "key": "_order_stock_reduced",
              "value": "yes"
            }
          ],
          "line_items": [
            {
              "id": 315,
              "name": "Woo Single #1",
              "product_id": 93,
              "variation_id": 0,
              "quantity": 2,
              "tax_class": "",
              "subtotal": "6.00",
              "subtotal_tax": "0.45",
              "total": "6.00",
              "total_tax": "0.45",
              "taxes": [
                {
                  "id": 75,
                  "total": "0.45",
                  "subtotal": "0.45"
                }
              ],
              "meta_data": [],
              "sku": "",
              "price": 3
            },
            {
              "id": 316,
              "name": "Ship Your Idea &ndash; Color: Black, Size: M Test",
              "product_id": 22,
              "variation_id": 23,
              "quantity": 1,
              "tax_class": "",
              "subtotal": "12.00",
              "subtotal_tax": "0.90",
              "total": "12.00",
              "total_tax": "0.90",
              "taxes": [
                {
                  "id": 75,
                  "total": "0.9",
                  "subtotal": "0.9"
                }
              ],
              "meta_data": [
                {
                  "id": 2095,
                  "key": "pa_color",
                  "value": "black"
                },
                {
                  "id": 2096,
                  "key": "size",
                  "value": "M Test"
                }
              ],
              "sku": "Bar3",
              "price": 12
            }
          ],
          "tax_lines": [
            {
              "id": 318,
              "rate_code": "US-CA-STATE TAX",
              "rate_id": 75,
              "label": "State Tax",
              "compound": false,
              "tax_total": "1.35",
              "shipping_tax_total": "0.00",
              "meta_data": []
            }
          ],
          "shipping_lines": [
            {
              "id": 317,
              "method_title": "Flat Rate",
              "method_id": "flat_rate",
              "total": "10.00",
              "total_tax": "0.00",
              "taxes": [],
              "meta_data": []
            }
          ],
          "fee_lines": [],
          "coupon_lines": [],
          "refunds": [],
          "_links": {
            "self": [
              {
                "href": "https://example.com/wp-json/wc/v3/orders/727"
              }
            ],
            "collection": [
              {
                "href": "https://example.com/wp-json/wc/v3/orders"
              }
            ]
          }
        },
        {
          "id": 723,
          "parent_id": 0,
          "number": "723",
          "order_key": "wc_order_58d17c18352",
          "created_via": "checkout",
          "version": "3.0.0",
          "status": "completed",
          "currency": "USD",
          "date_created": "2017-03-21T16:16:00",
          "date_created_gmt": "2017-03-21T19:16:00",
          "date_modified": "2017-03-21T16:54:51",
          "date_modified_gmt": "2017-03-21T19:54:51",
          "discount_total": "0.00",
          "discount_tax": "0.00",
          "shipping_total": "10.00",
          "shipping_tax": "0.00",
          "cart_tax": "0.00",
          "total": "39.00",
          "total_tax": "0.00",
          "prices_include_tax": false,
          "customer_id": 26,
          "customer_ip_address": "127.0.0.1",
          "customer_user_agent": "mozilla/5.0 (x11; ubuntu; linux x86_64; rv:52.0) gecko/20100101 firefox/52.0",
          "customer_note": "",
          "billing": {
            "first_name": "João",
            "last_name": "Silva",
            "company": "",
            "address_1": "Av. Brasil, 432",
            "address_2": "",
            "city": "Rio de Janeiro",
            "state": "RJ",
            "postcode": "12345-000",
            "country": "BR",
            "email": "joao.silva@example.com",
            "phone": "(11) 1111-1111"
          },
          "shipping": {
            "first_name": "João",
            "last_name": "Silva",
            "company": "",
            "address_1": "Av. Brasil, 432",
            "address_2": "",
            "city": "Rio de Janeiro",
            "state": "RJ",
            "postcode": "12345-000",
            "country": "BR"
          },
          "payment_method": "bacs",
          "payment_method_title": "Direct bank transfer",
          "transaction_id": "",
          "date_paid": null,
          "date_paid_gmt": null,
          "date_completed": "2017-03-21T16:54:51",
          "date_completed_gmt": "2017-03-21T19:54:51",
          "cart_hash": "5040ce7273261e31d8bcf79f9be3d279",
          "meta_data": [
            {
              "id": 13023,
              "key": "_download_permissions_granted",
              "value": "yes"
            }
          ],
          "line_items": [
            {
              "id": 311,
              "name": "Woo Album #2",
              "product_id": 87,
              "variation_id": 0,
              "quantity": 1,
              "tax_class": "",
              "subtotal": "9.00",
              "subtotal_tax": "0.00",
              "total": "9.00",
              "total_tax": "0.00",
              "taxes": [],
              "meta_data": [],
              "sku": "",
              "price": 9
            },
            {
              "id": 313,
              "name": "Woo Ninja",
              "product_id": 34,
              "variation_id": 0,
              "quantity": 1,
              "tax_class": "",
              "subtotal": "20.00",
              "subtotal_tax": "0.00",
              "total": "20.00",
              "total_tax": "0.00",
              "taxes": [],
              "meta_data": [],
              "sku": "",
              "price": 20
            }
          ],
          "tax_lines": [],
          "shipping_lines": [
            {
              "id": 312,
              "method_title": "Flat rate",
              "method_id": "flat_rate:25",
              "total": "10.00",
              "total_tax": "0.00",
              "taxes": [],
              "meta_data": [
                {
                  "id": 2057,
                  "key": "Items",
                  "value": "Woo Album #2 &times; 1"
                }
              ]
            }
          ],
          "fee_lines": [],
          "coupon_lines": [],
          "refunds": [
            {
              "id": 726,
              "refund": "",
              "total": "-10.00"
            },
            {
              "id": 724,
              "refund": "",
              "total": "-9.00"
            }
          ],
          "_links": {
            "self": [
              {
                "href": "https://example.com/wp-json/wc/v3/orders/723"
              }
            ],
            "collection": [
              {
                "href": "https://example.com/wp-json/wc/v3/orders"
              }
            ],
            "customer": [
              {
                "href": "https://example.com/wp-json/wc/v3/customers/26"
              }
            ]
          }
        }
      ]');
    }

    public static function productData() : array {
      return json_decode('[
        {
          "id": 799,
          "name": "Ship Your Idea",
          "slug": "ship-your-idea-22",
          "permalink": "https://example.com/product/ship-your-idea-22/",
          "date_created": "2017-03-23T17:03:12",
          "date_created_gmt": "2017-03-23T20:03:12",
          "date_modified": "2017-03-23T17:03:12",
          "date_modified_gmt": "2017-03-23T20:03:12",
          "type": "variable",
          "status": "publish",
          "featured": false,
          "catalog_visibility": "visible",
          "description": "<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>\n",
          "short_description": "<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>\n",
          "sku": "",
          "price": "",
          "regular_price": "",
          "sale_price": "",
          "date_on_sale_from": null,
          "date_on_sale_from_gmt": null,
          "date_on_sale_to": null,
          "date_on_sale_to_gmt": null,
          "price_html": "",
          "on_sale": false,
          "purchasable": false,
          "total_sales": 0,
          "virtual": false,
          "downloadable": false,
          "downloads": [],
          "download_limit": -1,
          "download_expiry": -1,
          "external_url": "",
          "button_text": "",
          "tax_status": "taxable",
          "tax_class": "",
          "manage_stock": false,
          "stock_quantity": null,
          "stock_status": "instock",
          "backorders": "no",
          "backorders_allowed": false,
          "backordered": false,
          "sold_individually": false,
          "weight": "",
          "dimensions": {
            "length": "",
            "width": "",
            "height": ""
          },
          "shipping_required": true,
          "shipping_taxable": true,
          "shipping_class": "",
          "shipping_class_id": 0,
          "reviews_allowed": true,
          "average_rating": "0.00",
          "rating_count": 0,
          "related_ids": [
            31,
            22,
            369,
            414,
            56
          ],
          "upsell_ids": [],
          "cross_sell_ids": [],
          "parent_id": 0,
          "purchase_note": "",
          "categories": [
            {
              "id": 9,
              "name": "Clothing",
              "slug": "clothing"
            },
            {
              "id": 14,
              "name": "T-shirts",
              "slug": "t-shirts"
            }
          ],
          "tags": [
            {
              "id": 19,
              "name": "Vacations",
              "slug": "vacations"
            }
          ],
          "images": [
            {
              "id": 795,
              "date_created": "2017-03-23T14:03:08",
              "date_created_gmt": "2017-03-23T20:03:08",
              "date_modified": "2017-03-23T14:03:08",
              "date_modified_gmt": "2017-03-23T20:03:08",
              "src": "https://example.com/wp-content/uploads/2017/03/T_4_front-11.jpg",
              "name": "",
              "alt": ""
            },
            {
              "id": 796,
              "date_created": "2017-03-23T14:03:09",
              "date_created_gmt": "2017-03-23T20:03:09",
              "date_modified": "2017-03-23T14:03:09",
              "date_modified_gmt": "2017-03-23T20:03:09",
              "src": "https://example.com/wp-content/uploads/2017/03/T_4_back-10.jpg",
              "name": "",
              "alt": ""
            },
            {
              "id": 797,
              "date_created": "2017-03-23T14:03:10",
              "date_created_gmt": "2017-03-23T20:03:10",
              "date_modified": "2017-03-23T14:03:10",
              "date_modified_gmt": "2017-03-23T20:03:10",
              "src": "https://example.com/wp-content/uploads/2017/03/T_3_front-10.jpg",
              "name": "",
              "alt": ""
            },
            {
              "id": 798,
              "date_created": "2017-03-23T14:03:11",
              "date_created_gmt": "2017-03-23T20:03:11",
              "date_modified": "2017-03-23T14:03:11",
              "date_modified_gmt": "2017-03-23T20:03:11",
              "src": "https://example.com/wp-content/uploads/2017/03/T_3_back-10.jpg",
              "name": "",
              "alt": ""
            }
          ],
          "attributes": [
            {
              "id": 6,
              "name": "Color",
              "position": 0,
              "visible": false,
              "variation": true,
              "options": [
                "Black",
                "Green"
              ]
            },
            {
              "id": 0,
              "name": "Size",
              "position": 0,
              "visible": true,
              "variation": true,
              "options": [
                "S",
                "M"
              ]
            }
          ],
          "default_attributes": [],
          "variations": [],
          "grouped_products": [],
          "menu_order": 0,
          "meta_data": [],
          "_links": {
            "self": [
              {
                "href": "https://example.com/wp-json/wc/v3/products/799"
              }
            ],
            "collection": [
              {
                "href": "https://example.com/wp-json/wc/v3/products"
              }
            ]
          }
        },
        {
          "id": 794,
          "name": "Premium Quality",
          "slug": "premium-quality-19",
          "permalink": "https://example.com/product/premium-quality-19/",
          "date_created": "2017-03-23T17:01:14",
          "date_created_gmt": "2017-03-23T20:01:14",
          "date_modified": "2017-03-23T17:01:14",
          "date_modified_gmt": "2017-03-23T20:01:14",
          "type": "simple",
          "status": "publish",
          "featured": false,
          "catalog_visibility": "visible",
          "description": "<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>\n",
          "short_description": "<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>\n",
          "sku": "",
          "price": "21.99",
          "regular_price": "21.99",
          "sale_price": "",
          "date_on_sale_from": null,
          "date_on_sale_from_gmt": null,
          "date_on_sale_to": null,
          "date_on_sale_to_gmt": null,
          "price_html": "<span class=\"woocommerce-Price-amount amount\"><span class=\"woocommerce-Price-currencySymbol\">&#36;</span>21.99</span>",
          "on_sale": false,
          "purchasable": true,
          "total_sales": 0,
          "virtual": false,
          "downloadable": false,
          "downloads": [],
          "download_limit": -1,
          "download_expiry": -1,
          "external_url": "",
          "button_text": "",
          "tax_status": "taxable",
          "tax_class": "",
          "manage_stock": false,
          "stock_quantity": null,
          "stock_status": "instock",
          "backorders": "no",
          "backorders_allowed": false,
          "backordered": false,
          "sold_individually": false,
          "weight": "",
          "dimensions": {
            "length": "",
            "width": "",
            "height": ""
          },
          "shipping_required": true,
          "shipping_taxable": true,
          "shipping_class": "",
          "shipping_class_id": 0,
          "reviews_allowed": true,
          "average_rating": "0.00",
          "rating_count": 0,
          "related_ids": [
            463,
            47,
            31,
            387,
            458
          ],
          "upsell_ids": [],
          "cross_sell_ids": [],
          "parent_id": 0,
          "purchase_note": "",
          "categories": [
            {
              "id": 9,
              "name": "Clothing",
              "slug": "clothing"
            },
            {
              "id": 14,
              "name": "T-shirts",
              "slug": "t-shirts"
            }
          ],
          "tags": [],
          "images": [
            {
              "id": 792,
              "date_created": "2017-03-23T14:01:13",
              "date_created_gmt": "2017-03-23T20:01:13",
              "date_modified": "2017-03-23T14:01:13",
              "date_modified_gmt": "2017-03-23T20:01:13",
              "src": "https://example.com/wp-content/uploads/2017/03/T_2_front-4.jpg",
              "name": "",
              "alt": ""
            },
            {
              "id": 793,
              "date_created": "2017-03-23T14:01:14",
              "date_created_gmt": "2017-03-23T20:01:14",
              "date_modified": "2017-03-23T14:01:14",
              "date_modified_gmt": "2017-03-23T20:01:14",
              "src": "https://example.com/wp-content/uploads/2017/03/T_2_back-2.jpg",
              "name": "",
              "alt": ""
            }
          ],
          "attributes": [],
          "default_attributes": [
            {
              "id": 6,
              "name": "Color",
              "option": "black"
            },
            {
              "id": 0,
              "name": "Size",
              "option": "S"
            }
          ],
          "variations": [],
          "grouped_products": [],
          "menu_order": 0,
          "meta_data": [],
          "_links": {
            "self": [
              {
                "href": "https://example.com/wp-json/wc/v3/products/794"
              }
            ],
            "collection": [
              {
                "href": "https://example.com/wp-json/wc/v3/products"
              }
            ]
          }
        }
      ]');
    }
}