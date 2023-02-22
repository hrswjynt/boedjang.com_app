<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $connection = 'mysql2';
    protected $table = 'Abs_list_outlet';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'nama','koderegion','alamat',
        'no_hp','email','no_mesin',
        'kode','namaregion','ip','toleransi'
    ];
}
