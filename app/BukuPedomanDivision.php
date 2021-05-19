<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BukuPedomanDivision extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'bpm_division';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'id','name','description','created_at','updated_at'];
}

