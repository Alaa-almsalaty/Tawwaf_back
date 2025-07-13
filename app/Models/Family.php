<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Family extends Model
{
    use HasFactory, SoftDeletes , BelongsToTenant;

    protected $guarded = ['id'];

    public function tenants()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function familyMaster()
    {
        return $this->belongsTo(Client::class, 'family_master_id');
    }

    public function familyMembers()
    {
        return $this->hasMany(Client::class, 'family_id');
    }


}
