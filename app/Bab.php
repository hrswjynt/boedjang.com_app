<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bab extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'bab';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'id','name','sequence','description','created_at','updated_at'];

}

