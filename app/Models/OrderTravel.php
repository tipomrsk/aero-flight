<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

/**
 * Class OrderTravel
 *
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property string $origin
 * @property string $destination
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property User $user
 *
 * @package App\Models
 */
class OrderTravel extends Model
{
    use SoftDeletes;

    protected $table = 'order_travel';

    protected $casts = [
        'user_id' => 'int',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected $fillable = [
        'uuid',
        'user_id',
        'origin',
        'destination',
        'start_date',
        'end_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
