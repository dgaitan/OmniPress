<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Organization;
use App\Models\Service;
use App\Services\ServiceService;
use App\Enums\ServiceType;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function create_user(array $args = array()) : User {
        if (!$args) {
            $args = array(
                'name' => 'John',
                'email' => 'john@doe.com',
                'password' => 'secret'
            );
        }

        $args['password'] = Hash::make($args['password']);
        return User::create($args);
    }

    protected function create_org(array $args = []) : Organization {
        if (!$args) {
            $user = $this->create_user();
            $args = array(
                'name' => 'Org 1',
                'is_default' => true,
                'status' => 1,
                'owner_id' => $user->id
            );
        }

        return Organization::create($args);
    }

    protected function create_service(array $args = [], array $access = []) : Service {
        if (!$args) {
            $org = $this->create_org();
            $args = array(
                'name' => 'kindhumans',
                'type' => ServiceType::WOOCOMMERCE,
                'creator_id' => $org->owner->id,
                'organization_id' => $org->id
            );
            
        }
        
        if (!$access) {
            $access = [
                'domain' => env('WOO_CUSTOMER_DOMAIN'),
                'customer_key' => env('WOO_CUSTOMER_KEY'),
                'customer_secret' => env('WOO_CUSTOMER_SECRET')
            ];
        }
        
        return (new ServiceService)->create($args, $access);
    }
    
}
