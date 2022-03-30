<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class MembershipExport implements FromArray, WithHeadings
{

    protected array $memberships;

    public function __construct(array $memberships) {
        $this->memberships = $memberships;
    }

    public function headings(): array
    {
        return [
            'Membership ID',
            'Customer',
            'Status',
            'Shipping Status',
            'Gift Product',
            'Start At',
            'End At',
            'Kind Cash'
        ];
    }

    /**
    * @return array
    */
    public function array(): array
    {
        return $this->memberships;
    }
}
