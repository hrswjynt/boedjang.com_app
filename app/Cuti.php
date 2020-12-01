<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $connection = 'mysql2';
    protected $table = 'Abs_Cuti_copy';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'id','Tanggal_input','Nama',
        'NIP','Tanggal_awal','Tanggal_akhir',
        'Ket','User','Cabang','page'
    ];
}
