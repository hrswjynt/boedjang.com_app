<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SopHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'sop_history';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'id','sop','user','date'];
}

