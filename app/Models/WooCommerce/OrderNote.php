<?php

namespace App\Models\WooCommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WooCommerce\OrderNote
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $order_id
 * @property string|null $author
 * @property string $date_created
 * @property string|null $note
 * @property bool $customer_note
 * @property bool $added_by_user
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote whereAddedByUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote whereCustomerNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote whereDateCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderNote whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderNote extends Model
{
    use HasFactory;
}
