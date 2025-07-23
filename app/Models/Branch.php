<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Branch extends Model
{
    use HasFactory, SoftDeletes ;

    protected $guarded = ['id'];

    public function tenants()
    {
        return $this->belongsTo(Tenant::class , 'tenant_id');
    }



}
