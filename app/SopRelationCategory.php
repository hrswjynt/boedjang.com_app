<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SopRelationCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'sop_relation_category';
    protected $primaryKey = ['id_sop','id_category'];
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'id_sop','id_category'];
}

