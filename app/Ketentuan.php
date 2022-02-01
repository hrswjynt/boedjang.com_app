<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ketentuan extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'ketentuan';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'id','slug','title','content','created_at','updated_at','publish','sequence'];
}

