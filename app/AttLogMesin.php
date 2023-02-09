<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttLogMesin extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $connection = 'mysql3';
    protected $table = 'att_log';
    protected $primaryKey = ['sn', 'scan_date', 'pin'];
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'sn','scan_date','pin',
    ];
}