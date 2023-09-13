<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReadinessValidator extends Model
{
    protected $table = 'readiness_validator';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'date',
        'readiness_matrix',
        'validator'
    ];

    public function matrix()
    {
        return $this->belongsTo(ReadinessMatrix::class, 'readiness_matrix', 'id');
    }
}
