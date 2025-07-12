<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

}
