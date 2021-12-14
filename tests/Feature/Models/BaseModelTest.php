<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

abstract class BaseModelTest extends TestCase {
    use RefreshDatabase; // This helps to restore database after run testings

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
}