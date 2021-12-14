<?php

namespace Tests\Feature\Models;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OrganizationModelTest extends BaseModelTest {

    public function test_organization_relation() : void {
        $user = $this->create_user();

        $org = new Organization();
        $org->name = "Org 1";
        $org->owner_id = $user->id;
        $org->is_default = true;
        $org->status = 1;
        $org->save();

        // Test owner
        $this->assertEquals($user->id, $org->owner->id);
        $this->assertEquals($user->name, $org->owner->name);
        $this->assertEquals($user->email, $org->owner->email);

        // test Owner data
        $this->assertEquals("Org 1", $org->name);
        $this->assertTrue($org->is_default);
        $this->assertEquals(1, $org->status);

        $this->assertEquals(1, $user->my_organizations()->count());
        $this->assertEquals('Org 1', $user->my_organizations()->first()->name);
    }

    public function test_users_on_organization() : void {
        $user1 = User::create([
            'name' => 'John 1',
            'email' => 'john@doe1.com',
            'password' => Hash::make('secret')
        ]);

        $user2 = User::create([
            'name' => 'John 2',
            'email' => 'john@doe2.com',
            'password' => Hash::make('secret')
        ]);

        $owner = $this->create_user();

        $org = new Organization();
        $org->name = "Org 1";
        $org->owner_id = $owner->id;
        $org->is_default = true;
        $org->status = 1;
        $org->save();

        $org->members()->attach([$owner->id, $user1->id, $user2->id]);

        $this->assertEquals(3, $org->members()->count());
        
        $this->assertEquals("Org 1", $user1->organizations()->first()->name);
        
        $new_member = $org->members()->create(array(
            'name' => 'David',
            'email' => 'gaitan@drums.com',
            'password' => Hash::make('secret')
        ));

        $this->assertEquals('David', $new_member->name);
        $this->assertEquals(4, $org->members()->count());
    }

}