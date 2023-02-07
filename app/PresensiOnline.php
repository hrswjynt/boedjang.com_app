<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresensiOnline extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'presensi_online';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'id','date','nip','ip',
        'gambar','latitude','longitude','jenis_absen','status'];
}