<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReadinessMatrix extends Model
{
    protected $table = 'readiness_matrix';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'readiness_kompetensi',
        'staff',
        'staff_valid',
        'staff_valid_date',
        'atasan',
        'atasan_valid',
        'atasan_valid_date',
        'created_at',
        'updated_at'
    ];

    public function userStaff()
    {
        return $this->belongsTo(User::class, 'staff', 'id');
    }

    public function validator()
    {
        return $this->hasOne(ReadinessValidator::class, 'readiness_matrix', 'id');
    }

    public function userAtasan()
    {
        return $this->belongsTo(User::class, 'atasan', 'id');
    }

    public function kompetensi()
    {
        return $this->belongsTo(ReadinessKompetensi::class, 'readiness_kompetensi', 'id');
    }
}
