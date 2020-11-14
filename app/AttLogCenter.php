<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttLogCenter extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $connection = 'mysql2';
    protected $table = 'Abs_att_log_center';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'id','unique','tgl_absen',
        'hari','lokasi_absen','nip',
        'nama','cabang','status','status2',
        'jam_masuk','jam_pulang','jam_kerja','timediff_masuk',
        'timediff_pulang','timediff_jam_kerja','pot_masuk','pot_pulang',
        'pot_jam_kerja','pot_no_absen',
        'pot_extra','pot_alfa_a','pot_izin',
        'pot_sakit','pot_alfa_b','pulang_awal',
        'kasbon','pinjaman','lembur','bonus_extra',
        'gaji','bonus_bln','bonus_khs','timediff_server',
        'tipe_absen','periode','kodedivisi','region',
    ];
}