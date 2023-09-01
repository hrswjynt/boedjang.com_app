<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KompetensiBagian extends Model
{
    protected $table = 'kompetensi_bagian';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'nama',
        'kode',
        'kompetensi_jenis'
    ];

    public function jenis()
    {
        return $this->belongsTo(KompetensiJenis::class, 'kompetensi_jenis', 'id');
    }

    public function kompetensi()
    {
        return $this->hasMany(Kompetensi::class, 'kompetensi_bagian', 'id');
    }
}
