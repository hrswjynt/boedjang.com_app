<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'type';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'id','name','sequence','description','created_at','updated_at'];
}

