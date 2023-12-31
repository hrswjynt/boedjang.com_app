<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Karyawan;
use DataTables;
use Auth;
use DB;
use File;
use Image;
use Hash;

class UserController extends Controller
{

    function __construct()
    {

    }

    public function index()
    {   
        if(Auth::user()->role == 1){
            return view('user.index')->with('page','user');
        }else{
            $message_type = 'danger';
            $message = 'Tidak memiliki hak akses untuk melihat data.';
            return redirect()->route('dashboard')->with($message_type,$message);
        }
        
    }

    public function getData(){
        $data = User::all();
        return $this->datatable($data);
    }

    public function datatable($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                if($data->role == 1){
                    $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("user.show",$data->id).'" title="Info"><i class="fa fa-search"></i> Info </a>';
                }else if(Auth::user()->role == 1){
                    $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("user.show",$data->id).'" title="Info"><i class="fa fa-search"></i> Info </a>';
                    $action .='<a class="btn btn-sm btn-warning btn-simple shadow" href="'.route("user.edit",$data->id).'" title="Edit"><i class="fa fa-edit"></i> Edit</a>';
                    $action .='<a href="'.route("user.delete",$data->id).'" class="btn btn-sm btn-danger btn-simple userDelete shadow" title="Hapus" data-id="'.$data->id.'"><i class="fa fa-trash"></i> Hapus</a>';
                }

                if($action == ''){
                    $action .= 'None';
                }
                $action .= '</div>';
                return $action;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }
    
    public function create(){
        if(Auth::user()->role == 1){
            return view('user.create')->with('page','user');
        }else{
            $message_type = 'danger';
            $message = 'Tidak memiliki hak akses untuk menambah data.';
            return redirect()->route('dashboard')->with($message_type,$message);
        }
        
    }
    
    public function store(Request $request)
    {   
        // dd($request->all());
        request()->validate([
            'name' => 'required',
            'username' => 'required',
        ]);
        DB::beginTransaction();
        $model = new User;
        if(User::where('username',$request->username)->first() !== null){
            DB::rollback();
            $message_type = 'danger';
            $message = 'Username yang digunakan tidak boleh sama dengan yang ada di database.';
            return redirect()->route('user.create')->withInput()->with($message_type,$message);
        }
        if($request->password != $request->password_confirm){
            DB::rollback();
            $message_type = 'danger';
            $message = 'Password harus sama dengan password konfirmasi.';
            return redirect()->route('user.create')->withInput()->with($message_type,$message);
        }
        $model->name = $request->name;
        $model->username = $request->username;
        $model->email = $request->email;
        // $model->cabang = $request->cabang;
        // $model->region = $request->region;
        $model->password = Hash::make($request->password);
        $model->role = $request->role;
        // $model->created_by = Auth::user()->id;
        if(!$model->save()) {
            DB::rollback();
            $message_type = 'danger';
            $message = 'Pengguna dengan username "<b>'.$model->username.'</b>" gagal dibuat.';
            return redirect()->route('user.create')->with($message_type,$message);
        }else{
            DB::commit();
            $message_type = 'success';
            $message = 'Pengguna dengan username "<b>'.$model->username.'</b>" berhasil dibuat.';
            return redirect()->route('user.index')->with($message_type,$message);
        }

    }
    
    public function show(User $user)
    {   
        $karyawan = Karyawan::select('alamat', 'NIK', 'No_HP')->where('NIP', $user->username)->first();
        if(Auth::user()->role == 1){
            return view('user.show')->with('user', $user)->with('karyawan',$karyawan)->with('page','user');
        }else{
            $message_type = 'danger';
            $message = 'Tidak memiliki hak akses untuk mengedit Orang lain.';
            return redirect()->route('dashboard')->with($message_type,$message);
        }
    }
    
    public function edit(User $user)
    {   
        $karyawan = Karyawan::select('alamat', 'NIK', 'No_HP')->where('NIP', $user->username)->first();
        if(Auth::user()->role == 1){
            return view('user.edit')->with('page','user')
                                        ->with('karyawan',$karyawan)
                                        ->with('user', $user);
        }else{
            if(Auth::user()->id != $user->id){
                $message_type = 'danger';
                $message = 'Tidak memiliki hak akses untuk mengedit Orang lain.';
                return redirect()->route('dashboard')->with($message_type,$message);
            }
            return view('user.edit')->with('page','dashboard')
                                        ->with('karyawan',$karyawan)
                                        ->with('user', $user);
        }
    }
    
    public function update(Request $request, User $user)
    {
        // dd($request->all());
        request()->validate([
            'name' => 'required',
            'username' => 'required',
        ]);
        DB::beginTransaction();
        if($user->role == 1){
            if(Auth::user()->role !== 1){
                $message_type = 'danger';
                $message = 'Tidak memiliki hak akses untuk mengedit Admin.';
                return redirect()->route('dashboard')->with($message_type,$message);
            }
        }

        if(Auth::user()->role !== 1){
            if(Auth::user()->id != $user->id){
                $message_type = 'danger';
                $message = 'Tidak memiliki hak akses untuk mengedit Orang lain.';
                return redirect()->route('dashboard')->with($message_type,$message);
            }
        }

        $karyawan = Karyawan::where('NIP', $user->username)->first();
        if($karyawan !== null){
            $karyawan->NIK = $request->nik;
            $karyawan->alamat = $request->alamat;
            $karyawan->No_HP = $request->no_hp;
            $karyawan->save();
        }
        
        $image_name = null;
        if($request->gambar !== null){
            $image = $request->file('gambar');
            $image_name = time() . 'profile.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/profile');
            $resize_image = Image::make($image->getRealPath());
            // $resize_image->resize(null, 300, function($constraint){
            //     $constraint->aspectRatio();
            // })->save($destinationPath . '/' . $image_name);
            $resize_image->save($destinationPath . '/' . $image_name);
        }

        $model = $user;
        if(Auth::user()->role == 1){
            if($user->username != $request->username){
                if(User::where('username',$request->username)->first() !== null){
                    DB::rollback();
                    $message_type = 'danger';
                    $message = 'Username yang digunakan tidak boleh sama dengan yang ada di database.';
                    return redirect()->route('user.edit',['user' => $model->id])->withInput()->with($message_type,$message);
                }
            }
        }
        if($request->password != $request->password_confirm){
            DB::rollback();
            $message_type = 'danger';
            $message = 'Password harus sama dengan password konfirmasi.';
            return redirect()->route('user.create')->withInput()->with($message_type,$message);
        }
        $model->name = $request->name;
        $model->username = $request->username;
        $model->email = $request->email;
        if($image_name != null){
            if($model->gambar !==null){
                $image_path = public_path('/images/profile/'.$model->gambar);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $model->gambar = $image_name;
        }
        if($request->password != null){
            $model->password = Hash::make($request->password);
        }
        if(Auth::user()->role == 1){
            $model->role = $request->role;
            $model->presensi_online = $request->presensi_online;
        }
        // dd(Auth::user()->role !== 4);
        if(Auth::user()->role == 1){
            if(!$model->save()) {
                DB::rollback();
                $message_type = 'danger';
                $message = 'Pengguna dengan username "<b>'.$model->username.'</b>" gagal diupdate.';
                return redirect()->route('user.edit',['user' => $model->id])->with($message_type,$message);
            }else{
                DB::commit();
                $message_type = 'success';
                $message = 'Pengguna dengan username "<b>'.$model->username.'</b>" berhasil diupdate.';
                return redirect()->route('user.index')->with($message_type,$message);
            }
        }else{
            if(!$model->save()) {
                DB::rollback();
                $message_type = 'danger';
                $message = 'Pengguna dengan username "<b>'.$model->username.'</b>" gagal diupdate.';
                return redirect()->route('user.edit',['user' => $model->id])->with($message_type,$message);
            }else{
                DB::commit();
                $message_type = 'success';
                $message = 'Pengguna dengan username "<b>'.$model->username.'</b>" berhasil diupdate.';
                return redirect()->route('dashboard')->with($message_type,$message);
            }
        }
        

        
   }

    public function delete($id){
        DB::beginTransaction();
        try{
            $user = User::find($id);
            $username = $user->username;
            if($user->role !== 1){
                $user->delete();
            }
            DB::commit();
            return response()->json([
                'message' => 'Pengguna dengan username "<b>'.$username.'</b>" berhasil dihapus.',
                'type'=> 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Pengguna dengan username "<b>'.$username.'</b>" gagal dihapus.',
                'type'=> 'danger',
            ]);
        }
    }

}
