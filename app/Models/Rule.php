<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;

    protected $table = 'rules';

    protected $fillable = [
        'rule_name',
        'value',
        'active',
        'priority',
        'courier_service_id',
        'eshop_id',
        'subject_id',
        'predicate_id',
        'created_at',
        'updated_at'
    ];

    public function CourierService()
    {
        return $this->belongsTo('App\Models\CourierService');
    }

    public function eshop()
    {
        return $this->belongsTo('App\Models\Eshop');
    }

    public function subject()
    {
        return $this->belongsTo('App\Models\Subject');
    }

    public function predicate()
    {
        return $this->belongsTo('App\Models\Predicate');
    }
}
