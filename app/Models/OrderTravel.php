<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Ramsey\Uuid\Uuid;

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
    use HasFactory;
    use HasUuids;

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

    protected $hidden = [
        'id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Generate a new UUID for the model.
     */
    public function newUniqueId(): string
    {
        return (string) Uuid::uuid4();
    }

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * Get the user that owns the order travel.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Adiona o name do User no retorno da consulta
     */
    public function getNameAttribute(): string
    {
        return $this->user ? $this->user->name : '';
    }

    /**
     * Get the start date formatted.
     */
    public function getStartDateAttribute(\DateTimeInterface|\Carbon\WeekDay|\Carbon\Month|string|int|float|null $value): string
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * Get the end date formatted.
     */
    public function getEndDateAttribute(\DateTimeInterface|\Carbon\WeekDay|\Carbon\Month|string|int|float|null $value): string
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
}
