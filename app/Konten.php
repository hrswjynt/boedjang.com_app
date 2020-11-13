<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Konten extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'content';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'id','type','content',
        'created_at','updated_at'];
}
