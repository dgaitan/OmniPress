<?php

namespace App\Http\Clients\WooCommerce\Endpoints;

use App\Data\Http\MembershipData;

class MembershipEndpoint extends BaseEndpoint
{
    /**
     * Endpoint where it should goes to
     *
     * @var string
     */
    protected $endpoint = 'memberships';

    /**
     * Data processor.
     *
     * Should be an instance of Data
     */
    protected function getDataProcessor()
    {
        return MembershipData::class;
    }
}
