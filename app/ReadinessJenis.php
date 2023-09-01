<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReadinessJenis extends Model
{
    protected $table = 'readiness_jenis';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'nama',
        'readiness_kategori'
    ];

    public function kategori()
    {
        return $this->belongsTo(ReadinessKategori::class, 'readiness_kategori', 'id');
    }

    public function bagian()
    {
        return $this->hasMany(ReadinessBagian::class, 'readiness_jenis', 'id');
    }
}
