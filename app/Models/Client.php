<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'note',
        'family_id', // Foreign key to the family client, if applicable
        'visa_id', // Foreign key to the visa, if applicable
        'tenant_id', // Foreign key to the tenant
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function family()
    {
        return $this->belongsTo(Family::class, 'family_id');
    }

    public function muhram()
    {
        return $this->belongsTo(Client::class, 'MuhramID');
    }

    public function visa()
    {
        return $this->belongsTo(Visa::class , 'visa_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'clientrooms')
            ->withPivot('check_in', 'check_out')
            ->withTimestamps();
    }

}
