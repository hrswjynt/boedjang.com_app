<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class KompetensiKategori extends Model
{
    use Notifiable;

    protected $table = 'kompetensi_kategori';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'nama'
    ];

    public function jenis()
    {
        return $this->hasMany(KompetensiJenis::class, 'kompetensi_kategori', 'id');
    }
}
