<?php

namespace App\Models\SearchEngine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Index extends Model
{
    use HasFactory;
    use Searchable;

    /**
     * Attribute casts
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'array',
        'relations' => 'array'
    ];

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'content_id',
        'content_type',
        'title',
        'slug',
        'content',
        'meta',
        'relations'
    ];

    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return sprintf('%s_kindhumans_indexes', env('MEILISEARCH_PREFIX', 'dev'));
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'content_id',
            'content_type',
            'title',
            'slug',
            'content',
            'meta',
            'relations'
        ];
    }
}
