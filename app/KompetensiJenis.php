<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KompetensiJenis extends Model
{
    protected $table = 'kompetensi_jenis';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'nama',
        'kompetensi_kategori'
    ];

    public function kategori()
    {
        return $this->belongsTo(KompetensiKategori::class, 'kompetensi_kategori', 'id');
    }

    public function bagian()
    {
        return $this->hasMany(KompetensiBagian::class, 'kompetensi_jenis', 'id');
    }
}
