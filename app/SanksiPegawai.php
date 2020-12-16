<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SanksiPegawai extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $connection = 'mysql2';
    protected $table = 'sanksi_karyawan';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'id','nip','nama',
        'cabang','region','sanksi','tgl_awal',
        'tgl_akhir','status','keterangan','user_input','cabang_input'];
}