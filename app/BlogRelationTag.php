<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogRelationTag extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'blog_relation_tag';
    protected $primaryKey = ['id_blog','id_tag'];
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'id_blog','id_tag'];
}

