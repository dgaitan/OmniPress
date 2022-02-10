<?php

namespace App\Services;

use App\Models\Membership;
use App\Models\Organization;

class MembershipService {

    /**
     * Organization Object
     * 
     * @var Organization|null
     */
    protected $org;

    /**
     * Create an organization easily
     * 
     * @param array $args - must be only name, is_default and status
     * @param User $user - The owned user
     */
    public function create(array $args) : Organization {
        $org = Organization::create($args);
        $org->members()->attach($org->owner);
        $this->org = $org;

        return $org;
    }

    /**
     * Set an org instance
     * 
     * @param Organization
     */
    public function set_org(Organization $org) {
        $this->org = $org;
    }

    public function get_org() : Organization {
        return $this->org;
    }

    /**
     * Add member to an organization
     * 
     * @param User|array|string|integer $member
     * @return Organization
     */
    public function add_members($member) : Organization {
        if ($member instanceof User || is_array($member)) {
            $this->org->members()->attach($member);
        }

        if (is_string($member) || is_integer($member)) {
            $this->org->members()->attach((int) $member);
        }

        return $this->org;
    }
}