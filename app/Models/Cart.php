<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = ['visitor_id', 'package_id'];

    public function visitor()
    {
        return $this->belongsTo(User::class, 'visitor_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
