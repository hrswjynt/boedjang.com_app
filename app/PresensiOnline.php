<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

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


    public function scopeGetPresensi($query, $jenis_absen)
    {
        return $query->where('nip',Auth::user()->username)
                        ->whereDate('date', date('Y-m-d'))
                        ->where('jenis_absen', $jenis_absen)
                        ->where('status', 1);
    }
}