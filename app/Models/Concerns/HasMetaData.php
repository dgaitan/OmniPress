<?php

namespace App\Models\Concerns;

trait HasMetaData {

    /**
     * Get a meta value
     *
     * @param string $key
     * @return void
     */
    public function getMetaValue(string $key, mixed $default = null) {
        $metaData = collect($this->meta_data)->where('key', $key);

        if ($metaData->count() > 0) {
            return $metaData->pluck('value')[0];
        }

        return $default;
    }
}
