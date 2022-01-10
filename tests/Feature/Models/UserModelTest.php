<?php

namespace Tests\Feature\Models;

use DateTime;
use App\Models\User;

class UserModelTest extends BaseModelTest {
    
    public function test_user_creation_with_role() : void {
        $org = $this->create_org();
        $org->createRolesAndPermissions();
        setPermissionsTeamId($org->id);

        $user = User::create([
            'name' => 'John', 
            'email' => 'john@foo.com',
            'password' => 'secret'
        ]);
        
        $user->load('roles');
        $user->load('permissions');
        $user->assignRole('manager');
        $this->assertTrue($user->hasPermissionTo('can_add_user'));

        $newUser = User::create([
            'name' => 'New User',
            'email' => 'new@user.com',
            'password' => 'secret'
        ]);

        $newOrg = $this->create_org([
            'owner_id' => $newUser->id,
            'is_default' => false,
            'status' => 1,
            'name' => 'Org 2'
        ]);
        
        $newOrg->createRolesAndPermissions();
        setPermissionsTeamId($newOrg->id);

        $user->load('roles');
        $user->load('permissions');
        $user->assignRole('guest');
        $this->assertTrue($user->hasPermissionTo('can_edit_profile'));
        $this->assertFalse($user->hasPermissionTo('can_add_user'));

        setPermissionsTeamId($org->id);
        $user->load('roles');
        $user->load('permissions');
        $this->assertTrue($user->hasPermissionTo('can_add_user'));
    }
}