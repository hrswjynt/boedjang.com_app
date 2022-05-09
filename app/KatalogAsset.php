<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KatalogAsset extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'katalog_asset';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'id', 'master_bahan', 'brand_display', 'gambar', 'created_at', 'updated_at', 'description', 'sequence', 'name', 'harga_acuan'
    ];
}
