<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $connection = 'mysql2';
    protected $table = 'Absen';
    protected $primaryKey = 'No';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'No','kasbon1','kasbon2',
        'NIP','NAMA','Cabang',
        'Jabatan','Jabatan_temp','Jabatan_last','Agama',
        'No_HP','NIK','Tgl_lahir','No_Rek',
        'Bank','Status','Grade','Grade_temp',
        'Last_update_grade','Masa_kerja',
        'kode_jabatan','kartu','Tanggal_Masuk',
        'Jam_Kerja','Jam_kerja_shift','Jam_kerja_gaji',
        'Hari_Off','Day_name','Off_2','bulan',
        'Jam_masuk','Jam_pulang','Timediff','Gaji_pokok',
        'Last_update_gapok','Bonus_bulanan','Bonus_khusus',
        'Terakhir_Update','bpjs_ksht','bpjs_tkrj','bonus_ext',
        'region','kodedivisi','tambahan3bln','total_gaji',
        'max_kasbon_1','max_kasbon_all','jml_kasbon','sisa_cuti',
        'tgl_penangguhan','tesupdate','user_id'
    ];
}
