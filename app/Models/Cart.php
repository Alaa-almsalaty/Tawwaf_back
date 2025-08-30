<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = ['visitor', 'package'];

    public function visitor()
    {
        return $this->belongsTo(User::class, 'visitor');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package');
    }
}
