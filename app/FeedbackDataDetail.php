<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedbackDataDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $table = 'feedback_data_detail';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'id','header_id','feedback','poin'];

}

