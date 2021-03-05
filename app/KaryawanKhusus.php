<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KaryawanKhusus extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $connection = 'mysql2';
    protected $table = 'Abs_karyawan_khusus';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'id','no_id','nip','nama',
        'cabang','region'
    ];
}
