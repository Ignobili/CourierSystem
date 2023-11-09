<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subject';

    protected $fillable = [
        'name_subject',
        'class',
        'created_at',
        'updated_at'
    ];

    public function rules()
    {
        return $this->hasMany('App\Models\Rule');
    }
}
