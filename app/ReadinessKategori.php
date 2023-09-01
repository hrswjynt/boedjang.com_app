<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ReadinessKategori extends Model
{
    use Notifiable;

    protected $table = 'readiness_kategori';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'nama'
    ];

    public function jenis()
    {
        return $this->hasMany(ReadinessJenis::class, 'readiness_kategori', 'id');
    }
}
