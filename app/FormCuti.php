<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormCuti extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $connection = 'mysql2';
    protected $table = 'form_cuti';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'id','nip','nama',
        'lokasi_cuti','tgl_mulai','tgl_akhir',
        'off','status'
    ];
}