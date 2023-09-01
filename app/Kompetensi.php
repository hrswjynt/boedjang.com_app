<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kompetensi extends Model
{
    protected $table = 'kompetensi';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'nomor',
        'kompetensi',
        'kompetensi_bagian',
        'tipe',
    ];

    public function bagian()
    {
        return $this->belongsTo(KompetensiBagian::class, 'kompetensi_bagain', 'id');
    }
}
