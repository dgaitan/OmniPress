<?php

namespace App\Services\Sync;

use App\Jobs\SingleWooCommerceSync;
use App\Models\Sync;
use App\Services\BaseService;
use Illuminate\Validation\Rule;

class SyncronizeEntity extends BaseService
{
    /**
     * Service ruules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'content_type' => ['required', 'string', Rule::in(Sync::RESOURCES_TYPES)],
            'element_id' => ['required', 'integer']
        ];
    }

    /**
     * Handle the Service
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();

        SingleWooCommerceSync::dispatch($this->element_id, $this->content_type)
            ->delay(now()->addSeconds(5));


    }
}
