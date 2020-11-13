<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    /**
     * The attributes that are mass assignable.
     *  
     * @var array
     */
    protected $table = 'social_media';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'id','type','url',
        'created_at','updated_at'];
}
