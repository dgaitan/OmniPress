<?php

namespace App\Imports;

use App\Models\WooCommerce\Tag;
use App\Models\WooCommerce\Product;
use App\Models\WooCommerce\Category;
use App\Models\WooCommerce\ProductImage;
use App\Models\WooCommerce\ProductAttribute;
use App\Data\Http\ProductData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithProgressBar, WithHeadingRow
{
    use Importable;
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $data = ProductData::_fromCSV($row);
        
        $product = Product::firstOrNew(['product_id' => $row['id']]);
        $product->fill($data->toStoreData());
        $product->save();

        $this->syncCollection(
            'images',
            'image_id',
            $product,
            ProductImage::class,
            $data->images,
            'product_image_id',
            ['product_id' => $product->id]
        );

        $this->syncCollection(
            'attributes',
            'attribute_id',
            $product,
            ProductAttribute::class,
            $data->attributes,
            null,
            ['product_id' => $product->id]
        );
        
        $this->syncCollection(
            'categories', 
            'category_id', 
            $product, 
            Category::class, 
            $data->categories, 
            'woo_category_id'
        );

        $this->syncCollection(
            'tags',
            'tag_id',
            $product,
            Tag::class,
            $data->tags,
            'woo_tag_id'
        );

        return $product;
    }

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
                
                if (!is_null($customFieldId)) {
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
