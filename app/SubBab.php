<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubBab extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'sub_bab';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'id','bab','slug','title','content','created_at','updated_at','publish','sequence'];
}

