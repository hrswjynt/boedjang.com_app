<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReadinessBagian extends Model
{
    protected $table = 'readiness_bagian';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'nama',
        'kode',
        'readiness_jenis'
    ];

    public function jenis()
    {
        return $this->belongsTo(ReadinessJenis::class, 'readiness_jenis', 'id');
    }

    public function kompetensi()
    {
        return $this->hasMany(ReadinessKompetensi::class, 'readiness_bagian', 'id');
    }
}
