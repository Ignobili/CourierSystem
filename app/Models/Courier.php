<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;

    protected $table = 'courier';

    protected $fillable = [
        'courier_code',
        'courier_name',
        'eshop_id',
        'created_at',
        'updated_at'
    ];

    public function eshop()
    {
        return $this->belongsTo('App\Models\Eshop');
    }

    public function courier_service()
    {
        return $this->hasMany('App\Models\CourierService');
    }
}
