<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BukuPedoman extends Model
{
    protected $table = 'buku_pedoman';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'id','slug','title','content','created_at','updated_at','publish','gambar','division_display','reader'];
}

