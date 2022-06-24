<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

abstract class BaseImport implements ToModel, WithProgressBar, WithHeadingRow
{
    use Importable;

    /**
     * Import Collection stuffs related to a model
     *
     * @param  string  $collectionName [description]
     * @param  string  $fieldId        [description]
     * @param  [type]                             $modelToAttach  [description]
     * @param  string  $model          [description]
     * @param  \Spatie\LaravelData\DataCollection  $collection     [description]
     * @param  string|null  $customFieldId  [description]
     * @param  array  $extraFields    [description]
     * @return [type]                                             [description]
     */
    protected function syncCollection(
        string $collectionName,
        string $fieldId,
        $modelToAttach,
        string $model,
        \Spatie\LaravelData\DataCollection $collection,
        string $customFieldId = null,
        array $extraFields = []
    ): void {
        if ($collection) {
            $toAttach = [];

            foreach ($collection as $element) {
                $data = $element->toStoreData();

                $fieldIdName = is_null($customFieldId) ? $fieldId : $customFieldId;
                $modelElement = $model::firstOrNew([$fieldIdName => $data[$fieldId]]);

                if (! is_null($customFieldId)) {
                    $data[$customFieldId] = $data[$fieldId];
                }
                $data = [...$data, ...$extraFields];
                $modelElement->fill($data);
                $modelElement->save();

                $toAttach[] = $modelElement->id;
            }

            // If we can sync it automatically, let's do it
            if (method_exists($modelToAttach->{$collectionName}(), 'sync')) {
                $modelToAttach->{$collectionName}()->sync($toAttach);
            }
        }
    }
}
