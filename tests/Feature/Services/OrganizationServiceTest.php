<?php

namespace Tests\Feature\Services;

use Tests\Feature\Models\BaseModelTest;
use App\Services\OrganizationService;

class OrganizationServiceTest extends BaseModelTest {

    public function test_create_org_from_service() : void {
        $org_service = new OrganizationService;
        $owner = $this->create_user();
        $org_service->create([
            'name' => 'Org 1',
            'is_default' => true,
            'status' => 1,
            'owner_id' => $owner->id
        ], $owner);

        $org = $org_service->get_org();
        $this->assertEquals("Org 1", $org->name);
        $this->assertEquals('John', $org->owner->name);
        $this->assertEquals(1, $org->members()->count());
    }

    public function test_create_org_members_from_service() : void {
        $org_service = new OrganizationService;
        $owner = $this->create_user();
        $org_service->create([
            'name' => 'Org 1',
            'is_default' => true,
            'status' => 1,
            'owner_id' => $owner->id
        ], $owner);
        
        $user1 = $this->create_user([
            'name' => 'John 1',
            'email' => 'john@doe1.com',
            'password' => 'secret'
        ]);
        
        $org_service->add_members($user1);

        $this->assertEquals(2, $org_service->get_org()->members()->count());
    }
}