<?php

namespace Tests\Utils;

class WooCommerceClientTestResponses {

    public static function data() : array {
        return [
            'customers' => self::customerData(),
            'coupons' => self::couponData(),
            'orders' => self::orderData()
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
}