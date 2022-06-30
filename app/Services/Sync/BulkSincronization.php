<?php

namespace App\Services\Sync;

use App\Jobs\SingleWooCommerceSync;
use App\Models\Sync;
use App\Services\BaseService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Validation\Rule;

class BulkSincronization extends BaseService
{
    public function __construct(public $content_type, public $ids)
    {}

    /**
     * Service ruules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'content_type' => ['required', 'string', Rule::in(Sync::RESOURCES_TYPES)],
            'ids' => ['required', 'array']
        ];
    }

    /**
     * Handle Task
     *
     * @return void
     */
    public function handle()
    {
        $tasks = collect($this->ids)->map(function ($id) {
            return new SingleWooCommerceSync($id, $this->content_type);
        });

        Bus::chain($tasks)->dispatch();
    }
}
