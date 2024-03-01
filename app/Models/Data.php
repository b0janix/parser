<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Data extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicleId',
        'inhouseSellerId',
        'buyerId',
        'modelId',
        'saleDate',
        'buyDate'
    ];
}
