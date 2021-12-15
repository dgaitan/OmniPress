<?php

namespace App\Services;

use App\Models\Service;
use App\Enums\ServiceType;
use App\Data\Service\WooCommerceAccessData;

class ServiceService {

    /**
     * The service instance
     * 
     * @var Service
     */
    protected $service;

    public function create(array $args, array $accessData) : Service {
        $this->service = Service::create($args);

        if (!$this->service->type) {
            $this->service->type = ServiceType::default();
        }

        if ($this->service->type === ServiceType::WOOCOMMERCE) {
            $accessData = WooCommerceAccessData::from($accessData);
            $this->service->access = $accessData->getDataToStore();
        }

        var_dump(ServiceType::default());
        var_dump($this->service->type);

        $this->service->save();

        return $this->service;
    }
}