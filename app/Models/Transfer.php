<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    public const TRANSFER_SUCCESS = "success";
    public const TRANSFER_FAILED = "failed";
    public const TRANSFER_PENDING = "pending";
    public const TRANSFER_REVERSED = "reversed";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference',
        'amount',
        'source',
        'transfer_code',
        'reason',
        'paystack_id',
        'currency',
        'integration',
        'status',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
