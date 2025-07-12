<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'visa_type',
        'visa_number',
        'issue_date',
        'expiry_date',
        'duration_days',
        'note',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
