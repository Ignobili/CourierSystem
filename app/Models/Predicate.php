<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Predicate extends Model
{
    use HasFactory;

    protected $table = 'predicate';

    protected $fillable = [
        'name_predicate',
        'class',
        'created_at',
        'updated_at'
    ];

    public function rules()
    {
        return $this->hasMany('App\Models\Rule');
    }
}
