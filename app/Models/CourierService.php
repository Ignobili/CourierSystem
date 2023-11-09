<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierService extends Model
{
    use HasFactory;

    protected $table = 'courier_services';

    protected $fillable = [
        'service_code',
        'service_name',
        'courier_id',
        'created_at',
        'updated_at'
    ];

    public function courier()
    {
        return $this->belongsTo('App\Models\Courier');
    }

    public function rules()
    {
        return $this->hasMany('App\Models\Rule');
    }
}
