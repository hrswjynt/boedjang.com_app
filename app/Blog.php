<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'blogs';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'id','slug','title','content','created_at','updated_at','publish','gambar','tag_display','author'];

    // public function getRouteKeyName(){
    //     return 'slug';
    // }
}

