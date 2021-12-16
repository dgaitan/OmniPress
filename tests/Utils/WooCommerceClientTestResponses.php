<?php

namespace Tests\Utils;

class WooCommerceClientTestResponses {

    public static function data() : array {
        return [
            'customers' => self::customerData(),
            'coupons' => self::couponData()
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
}