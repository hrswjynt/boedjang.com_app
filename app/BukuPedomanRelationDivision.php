<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BukuPedomanRelationDivision extends Model
{

    protected $table = 'buku_pedoman_relation_division';
    protected $primaryKey = 'id_bpm';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'id_buku_pedoman','id_division'];
}

