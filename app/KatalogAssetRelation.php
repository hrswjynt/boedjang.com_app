<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KatalogAssetRelation extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'katalog_asset_relation';
    protected $primaryKey = ['id_katalog_asset','id_brand'];
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'id_katalog_asset','id_brand'];
}

