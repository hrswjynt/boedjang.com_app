<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReadinessKompetensi extends Model
{
    protected $table = 'readiness_kompetensi';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'nomor',
        'kompetensi',
        'readiness_bagian',
        'tipe',
    ];

    public function bagian()
    {
        return $this->belongsTo(ReadinessBagian::class, 'readiness_bagain', 'id');
    }

    public function matrix()
    {
        return $this->hasOne(ReadinessMatrix::class, 'readiness_kompetensi', 'id');
    }

    public function matrixes()
    {
        return $this->hasMany(ReadinessMatrix::class, 'readiness_kompetensi', 'id');
    }
}
