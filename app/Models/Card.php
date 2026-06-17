<?php

namespace App\Models;

use App\Models\Base\UniqueIdentity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\Card
 *
 * @property-read \App\Models\Address|null $Address
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Payment[] $Payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $Products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Card newQuery()
 * @method static \Illuminate\Database\Query\Builder|Card onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Card query()
 * @method static \Illuminate\Database\Query\Builder|Card withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Card withoutTrashed()
 * @mixin \Eloquent
 * @property string $id
 * @property string $email
 * @property string $mobile
 * @property int $price
 * @property int $count_custom
 * @property int $status
 * @property int|null $user_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CardFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereCountCustom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereUserId($value)
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 */
class Card extends Model
{
    use HasFactory, Notifiable, UniqueIdentity, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    #region Properties
    /******************
     * @var string[]
     * all of them are properties in database
     */
    protected $fillable = [
        'email',
        'mobile',
        'price',
        'status',
        'count_custom',
    ];
    #endregion

    #region Relationship
    /******************************
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * این ارتباط چند به چند بین محصول و سفارش می باشد
     */
    public function Products()
    {
        return $this->belongsToMany(Product::class);
    }

    /******************************
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * این ارتباط یک به یک بین آدرس و سفارش می باشد
     */
    public function Address()
    {
        return $this->hasOne(Address::class);
    }

    /******************************
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * این ارتباط چند به یک بین پرداخت و سفارش می باشد
     */
    public function Payments()
    {
        return $this->hasMany(Payment::class);
    }

    #endregion
}
