<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eshop extends Model
{
    use HasFactory;

    protected $table = 'eshop';

    protected $fillable = [
        'eshop_name',
        'created_at',
        'updated_at'
    ];

   public function couriers()
   {
       return $this->hasMany('App\Models\Courier');
   }

   public function rules()
   {
       return $this->hasMany('App\Models\Rule');
   }
}
