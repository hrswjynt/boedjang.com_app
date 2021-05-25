<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BukuPedomanRelationSop extends Model
{

    protected $table = 'buku_pedoman_relation_sop';
    protected $primaryKey = 'id_pedoman';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'id_buku_pedoman','id_sop'];
}

