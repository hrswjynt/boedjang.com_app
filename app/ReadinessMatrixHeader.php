<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReadinessMatrixHeader extends Model
{
    protected $table = 'readiness_matrix_header';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'date',
        'atasan',
        'staff',
        'bagian',
        'catatan'
    ];

    public function matrix()
    {
        return $this->hasMany(ReadinessMatrix::class, 'readiness_matrix_header', 'id');
    }

    public function dataBagian()
    {
        return $this->belongsTo(ReadinessBagian::class, 'bagian', 'id');
    }

    public function dataAtasan()
    {
        return $this->belongsTo(User::class, 'atasan', 'id');
    }

    public function dataStaff()
    {
        return $this->belongsTo(User::class, 'staff', 'id');
    }
}
