<?php

namespace App\Models\Concerns;

trait HasMetaData
{
    /**
     * Get a meta value
     *
     * @param  string  $key
     * @return void
     */
    public function getMetaValue(string $key, mixed $default = null)
    {
        $metaData = collect($this->meta_data)->where('key', $key);

        if ($metaData->count() > 0) {
            return $metaData->pluck('value')[0];
        }

        return $default;
    }

    /**
     * Update meta value
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function updateMetaValue(string $key, mixed $value = null)
    {
        if (is_null($this->getMetaValue($key))) {
            return null;
        }

        $this->meta_data = collect($this->meta_data)->map(function ($m) use ($key, $value) {
            if ($m['key'] === $key) {
                $m['value'] = $value;
            }

            return $m;
        });

        $this->save();

        return $value;
    }
}
