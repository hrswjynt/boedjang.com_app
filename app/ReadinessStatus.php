<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReadinessStatus extends Model
{
    protected $table = 'readiness_matrix_status';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'status'
    ];
}
