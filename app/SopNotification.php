<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SopNotification extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'sop_notification';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'id','sop','date','keterangan','read','admin','type'];

}

