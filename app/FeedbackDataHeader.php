<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedbackDataHeader extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'feedback_data_header';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'id','tgl','user','outlet','outlet_name','alasan1','alasan2','alasan3','atasan','puas'];

}

