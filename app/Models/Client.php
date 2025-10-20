<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Client extends Model
{
    use SoftDeletes, HasFactory , BelongsToTenant;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'note',
        'is_family_master', // Indicates if this client is the family master
        'register_date', // Date of registration
        'register_state', // State of registration (e.g., pending, completed)
        'family_id', // Foreign key to the family client, if applicable
        'tenant_id', // Foreign key to the tenant
        'personal_info_id', // Foreign key to the personal info
        'MuhramID', // Foreign key to the Muhram client
        'Muhram_relation', // Type of Muhram relationship
        'branch_id', // Foreign key to the branch
        'created_by', // Foreign key to the user who created the client
    ];
    protected $hidden = [
        // 'id',
        // 'created_at',
        // 'updated_at',
        // 'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'is_family_master' => 'boolean',
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

    public function personalInfo()
    {
        return $this->belongsTo(PersonalInfo::class, 'personal_info_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'clientrooms')
            ->withPivot('check_in', 'check_out')
            ->withTimestamps();
    }

}
