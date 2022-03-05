<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sop extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'sop';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'id','slug','title','content','created_at','updated_at','publish','gambar','google_drive','youtube','youtube2','jabatan_display','category_display','type'];

    // public function getRouteKeyName(){
    //     return 'slug';
    // }
}
