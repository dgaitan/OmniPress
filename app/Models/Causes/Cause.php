<?php

namespace App\Models\Causes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Causes\Cause
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $cause_id
 * @property string|null $name
 * @property string|null $cause_type
 * @property string|null $image_url
 * @property string|null $beneficiary
 * @method static \Illuminate\Database\Eloquent\Builder|Cause newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cause newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cause query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cause whereBeneficiary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cause whereCauseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cause whereCauseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cause whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cause whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cause whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cause whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cause whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Cause extends Model
{
    use HasFactory;

    /**
     * Causes types
     *
     * @var array
     */
    public static $causeTypes = [
        'fixed' => 'Collab for Cause',
        'bucket' => 'Bucket Cause'
    ];

    protected $fillable = [
        'cause_id',
        'name',
        'cause_type',
        'image_url',
        'beneficiary'
    ];

    /**
     * Get the cause type label
     *
     * @return string
     */
    public function getCauseType(): string
    {
        return self::$causeTypes[$this->cause_type];
    }

    /**
     * Get Image Url parsed
     *
     * @return string
     */
    public function getImage(): string
    {
        $imageUrl = '';

        if ($this->src) {
            $imageUrl = explode('wp-content/', $this->image_url);
            $imageUrl = sprintf(
                '%s/wp-content/%s',
                env('ASSET_DOMAIN', 'https://kindhumans.com'),
                end($imageUrl)
            );
        }

        return $imageUrl;
    }

    /**
     * Get valid cause types
     *
     * @return array
     */
    public static function getValidCauseTypes(): array
    {
        return array_keys(self::$causeTypes);
    }

    /**
     * This is the cause types list
     *
     * @return array
     */
    public static function getCauseTypes(): array
    {
        $types = [];

        foreach (self::$causeTypes as $slug => $label) {
            $types[] = [
                'slug' => $slug,
                'label' => $label
            ];
        }

        return $types;
    }
}
