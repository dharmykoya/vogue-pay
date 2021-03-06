<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferRecipient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "recipient_code",
        "account_number",
        "account_name",
        "bank_code",
        "bank_name"
    ];

    public function transferRecipients()
    {
        return $this->belongsTo(User::class);
    }
}
