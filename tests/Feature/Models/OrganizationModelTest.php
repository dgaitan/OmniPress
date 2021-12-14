<?php

namespace Tests\Feature\Models;

use App\Models\Organization;

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

        $this->assertEquals(1, $user->organizations()->count());
        $this->assertEquals('Org 1', $user->organizations()->first()->name);
    }

}