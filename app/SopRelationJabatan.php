<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SopRelationJabatan extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'sop_relation_jabatan';
    protected $primaryKey = ['id_sop','id_jabatan'];
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'id_sop','id_jabatan'];
}

