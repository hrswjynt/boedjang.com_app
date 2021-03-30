<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BpmRelationDivision extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'bpm_relation_division';
    protected $primaryKey = 'id_bpm';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'id_bpm','id_division'];
}

