<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Karyawan;
use App\User;
use DataTables;
use Auth;
use DB;

class TicketController extends Controller
{
    private $url = "https://api-tiket.sabangdigital.id";
    // private $url = "http://192.168.100.32:1438";
    private $token = "K6LA/bKakT5cpTcg65MBs.d/7/4WTLm6bhUnEgniLT6q5MrNNS3Pa";

    function __construct()
    {

    }

    public function login(){
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();
        
        if(Auth::user()->role == 1 || Auth::user()->role == 2 || $karyawan->Cabang == "HeadOffice"){
            if(!Auth::user()->ticket){
                $headers = [
                    'Content-Type: application/json',
                ];
                $data = [
                    "username" => Auth::user()->username,
                    "email" => Auth::user()->username."@boedjang.com",
                    "password" => "boedjang.com".Auth::user()->username,
                    // "role" => "1",
                ];
                $dataString = json_encode($data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $this->url.'/auth/signup');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);  
                $response = curl_exec($ch);
                $res = json_decode($response);
    
                $loginticket = User::find(Auth::user()->id);
                $loginticket->ticket = 1;
                $loginticket->token = $res->data->access_token;
                $loginticket->save();
            }else{
                $headers = [
                    'Content-Type: application/json',
                ];
                $data = [
                    "email" => Auth::user()->username."@boedjang.com",
                    "password" => "boedjang.com".Auth::user()->username,
                ];
                $dataString = json_encode($data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $this->url.'/auth/signin');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);  
                $response = curl_exec($ch);
                $res = json_decode($response);
                $loginticket = User::find(Auth::user()->id);
                $loginticket->token = $res->data->access_token;
                $loginticket->save();
            }
        }
    }

    public function pengajuan()
    {   
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();
        $user = User::where("username", Auth::user()->username)->first();

        $headers = [
            'Authorization: Bearer '.Auth::user()->token.'',
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket/details');
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $res = json_decode($response);
        if(property_exists($res, 'statusCode')){
            if($res->statusCode == 401){
                $res = null;
            }else if($res->statusCode == 500){
                $res = null;
            }
        }
        $data = $res->data;
        if($res !== null){
            $department = $data->departments;
            $platform = $data->platforms;
            $category = $data->categories;
            $priority = $data->priorities;
        }
        
        

        return view('ticket.pengajuan')->with('page','ticketpengajuan')
                                        ->with('karyawan',$karyawan)
                                        ->with('department',$department)
                                        ->with('category',$category)
                                        ->with('priority',$priority)
                                        ->with('platform',$platform);
    }

    public function pengajuanpost(Request $request)
    {   
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer '.$user->token,
            'Content-Type: application/json',
        ];
        $data = [
            "code" => 'SABI#'.date('dmyHis'),
            "title" => $request->title,
            "description" => $request->description,
            "platform_id" => $request->platform,
            "category_id" => $request->category,
            "priority_id" => $request->priority,
            "for_department" => $request->for_department,
            "from_department" => $request->from_department,
        ];

        try{
            $dataString = json_encode($data);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);  
            $response = curl_exec($ch);
            $res = json_decode($response);
            // dd($res);
            if($res->statusCode == 400){
                $message_type = 'danger';
                $message = 'Data Ticket "'.$user->username ." - ".$user->name  .'" gagal ditambahkan.';
                return redirect()->route('ticket.index')->with($message_type,$message);
            }else{
                $message_type = 'success';
                $message = 'Data Ticket "'.$user->username ." - ".$user->name  .'" berhasil ditambahkan.';
                return redirect()->route('ticket.index')->with($message_type,$message);
            }
            
        } catch (\Exception $e) {
            $message_type = 'danger';
            $message = 'Data Ticket "'.$user->username ." - ".$user->name  .'" gagal ditambahkan.';
            return redirect()->route('ticket.index')->with($message_type,$message);
        }
        
    }

    public function index()
    {   
        return view('ticket.index')->with('page','ticket');
    }

    public function getData(){
        // dd('tes');
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer '.$user->token,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket/all?user='.$user);
        curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket/all?from-currentuser');
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');
        // curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
        $response = curl_exec($ch);
        $res = json_decode($response);
        // dd($res);
        if(property_exists($res, 'statusCode')){
            if($res->statusCode == 401){
                $table = [];
            }else if($res->statusCode == 500){
                $table = [];
            }else{
                $table = $res->data;
            }
        }else if($res == null){
            $table = [];
        }
        
        
        // dd($res);
        return Datatables::of($table)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("ticket.show",$data->id).'" title="Info"><i class="fa fa-search"></i>Info</a>';
                $action .= '</div>';
                return $action;
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function delete($id){
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer '.$user->token,
            'Content-Type: application/json',
        ];
        try{ 
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket/'.$id);
            curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $res = json_decode($response);
            // dd($res);
            if(property_exists($res, 'statusCode')){
                if($res->statusCode == 200){
                    return response()->json([
                        'message' => 'Ticket "'.$id.'" berhasil dihapus!',
                        'type'=> 'success',
                    ]);
                }else{
                    return response()->json([
                        'message' => $res->message,
                        'type'=> 'danger',
                    ]);
                }
            }

            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ticket "'.$id.'" gagal dihapus!',
                'type'=> 'danger',
            ]);
        }
    }

    public function show($id)
    {   
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer '.$user->token,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket/all?user='.$user);
        curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket?id='.$id);
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');
        // curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
        $response = curl_exec($ch);
        $res = json_decode($response);

        if(property_exists($res, 'statusCode')){
            if($res->statusCode == 401){
                $res = null;
            }else if($res->statusCode == 500){
                $res = null;
            }
        }
        if($res == null){
            $message_type = 'danger';
            $message = 'Fitur Ticket tidak dapat diakses, coba gunakan kembali setelah beberapa saat lagi.';
            return redirect()->route('dashboard')->with($message_type,$message);
        }
        return view('ticket.pengajuanshow')->with('ticket', $res->data)->with('user',$user)->with('page','ticket');
    }

    public function manajemenTicket()
    {   
        return view('ticket.manajementicket')->with('page','manajementicket');
    }

    public function getDataManajemenTicket(){
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer '.$user->token,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket/get-all');
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $res = json_decode($response);
        // dd($res);
        if(property_exists($res, 'statusCode')){
            if($res->statusCode == 200){
                $table = $res->data;$table = $res->data;
            }else{
                $table = [];
            }
        }else if($res == null){
            $table = [];
        }
        
        
        // dd($res);
        return Datatables::of($table)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("ticket.show",$data->id).'" title="Info"><i class="fa fa-search"></i>Info</a>';
                $action .='<a class="btn btn-sm btn-warning btn-simple shadow" href="'.route("ticket.edit",$data->id).'" title="Edit"><i class="fa fa-edit"></i> Manage</a>';
                $action .='<a href="'.route("ticket.delete",$data->id).'" class="btn btn-sm btn-danger btn-simple shadow ticketDelete" title="Delete" data-id="'.$data->id.'"><i class="fa fa-trash"></i> Delete</a>';
                $action .= '</div>';
                return $action;
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function edit($id)
    {   
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer '.Auth::user()->token.'',
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket/details');
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response1 = curl_exec($ch);
        $res = json_decode($response1);

        curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket?id='.$id);
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response2 = curl_exec($ch);
        $ticket = json_decode($response2);

        // dd($ticket);
        // dd($res);
        if(property_exists($res, 'statusCode')){
            if($res->statusCode == 401){
                $res = null;
            }else if($res->statusCode == 500){
                $res = null;
            }
        }
        $data = $res->data;
        if($res !== null){
            $department = $data->departments;
            $platform = $data->platforms;
            $category = $data->categories;
            $priority = $data->priorities;
            $status = $data->statuses;
            $level = $data->levels;
            $userdata = $data->users;
        }
        
        if(property_exists($ticket, 'statusCode')){
            if($ticket->statusCode == 401){
                $ticket = null;
            }else if($ticket->statusCode == 500){
                $ticket = null;
            }
        }

        if($res !== null){
            $nip = $ticket->data->from_users->username;
            $user = Karyawan::where("NIP", $nip)->first();
        }else{
            $user = null;
        }
        if($user == null){
            $message_type = 'danger';
            $message = 'Data Pengguna Ticket tidak ditemukan.';
            return redirect()->route('manajementicket.index')->with($message_type,$message);
        }
        
        // dd($department);
        return view('ticket.pengajuanedit')->with('page','manajementicket')
                                        ->with('ticket',$ticket->data)
                                        ->with('user',$user)
                                        ->with('userdata',$userdata)
                                        ->with('karyawan',$karyawan)
                                        ->with('department',$department)
                                        ->with('category',$category)
                                        ->with('priority',$priority)
                                        ->with('status',$status)
                                        ->with('level',$level)
                                        ->with('platform',$platform);
    }

    public function update(Request $request, $id)
    {   
        // dd($request->all());
        // dd($id);
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer '.$user->token,
            'Content-Type: application/json',
        ];
        $data = [
            "title" => $request->title,
            "description" => $request->description,
            "platform_id" => $request->platform,
            "category_id" => $request->category,
            "priority_id" => $request->priority,
            "for_department" => $request->for_department,
            "from_department" => $request->from_department,
            "status_id"=> $request->status,
            "level_id"=> $request->level,
            'for_user'=> $request->for_user,
        ];
        

        try{
            $dataString = json_encode($data);
            // dd($dataString);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket/'.$id);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);  
            $response = curl_exec($ch);
            $res = json_decode($response);
            // dd($res);
            if($res->statusCode == 400){
                $message_type = 'danger';
                $message = 'Data Ticket "'.$user->username ." - ".$user->name  .'" gagal ditambahkan.';
                return redirect()->route('manajementicket.index')->with($message_type,$message);
            }else{
                $message_type = 'success';
                $message = 'Data Ticket "'.$user->username ." - ".$user->name  .'" berhasil ditambahkan.';
                return redirect()->route('manajementicket.index')->with($message_type,$message);
            }
            
        } catch (\Exception $e) {
            $message_type = 'danger';
            $message = 'Data Ticket "'.$user->username ." - ".$user->name  .'" gagal ditambahkan.';
            return redirect()->route('manajementicket.index')->with($message_type,$message);
        }
        
    }

    public function indexTask()
    {   
        return view('ticket.indextask')->with('page','task_ticket');
    }

    public function getDataTask(){
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer '.$user->token,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket/all?user='.$user);
        curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket/all?for-currentuser');
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');
        // curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
        $response = curl_exec($ch);
        $res = json_decode($response);
        // dd($res);
        if(property_exists($res, 'statusCode')){
            if($res->statusCode == 401){
                $table = [];
            }else if($res->statusCode == 500){
                $table = [];
            }else{
                $table = $res->data;
            }
        }else if($res == null){
            $table = [];
        }
        
        
        // dd($res);
        return Datatables::of($table)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action.= '<a class="btn btn-sm btn-info btn-simple shadow" href="'.route("task-ticket.show",$data->id).'" title="Info"><i class="fa fa-search"></i>Info</a>';
                $action .='<a class="btn btn-sm btn-warning btn-simple shadow" href="'.route("task-ticket.edit",$data->id).'" title="Edit"><i class="fa fa-edit"></i> Manage</a>';
                $action .= '</div>';
                return $action;
            })
            ->addIndexColumn()
            ->make(true);
    }


    public function editTask($id)
    {   
        $karyawan = Karyawan::where('NIP',Auth::user()->username)->first();
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer '.Auth::user()->token.'',
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket/details');
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response1 = curl_exec($ch);
        $res = json_decode($response1);

        curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket?id='.$id);
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response2 = curl_exec($ch);
        $ticket = json_decode($response2);

        // dd($ticket);
        // dd($res);
        if(property_exists($res, 'statusCode')){
            if($res->statusCode == 401){
                $res = null;
            }else if($res->statusCode == 500){
                $res = null;
            }
        }
        $data = $res->data;
        if($res !== null){
            $department = $data->departments;
            $platform = $data->platforms;
            $category = $data->categories;
            $priority = $data->priorities;
            $status = $data->statuses;
            $level = $data->levels;
            $userdata = $data->users;
        }
        
        if(property_exists($ticket, 'statusCode')){
            if($ticket->statusCode == 401){
                $ticket = null;
            }else if($ticket->statusCode == 500){
                $ticket = null;
            }
        }

        if($res !== null){
            $nip = $ticket->data->from_users->username;
            $user = Karyawan::where("NIP", $nip)->first();
        }else{
            $user = null;
        }
        if($user == null){
            $message_type = 'danger';
            $message = 'Data Pengguna Ticket tidak ditemukan.';
            return redirect()->route('task-ticket.index')->with($message_type,$message);
        }
        
        // dd($department);
        return view('ticket.taskedit')->with('page','task_ticket')
                                        ->with('ticket',$ticket->data)
                                        ->with('user',$user)
                                        ->with('userdata',$userdata)
                                        ->with('karyawan',$karyawan)
                                        ->with('department',$department)
                                        ->with('category',$category)
                                        ->with('priority',$priority)
                                        ->with('status',$status)
                                        ->with('level',$level)
                                        ->with('platform',$platform);
    }

    public function updateTask(Request $request, $id)
    {   
        // dd($request->all());
        // dd($id);
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer '.$user->token,
            'Content-Type: application/json',
        ];
        $data = [
            // "title" => $request->title,
            // "description" => $request->description,
            // "platform_id" => $request->platform,
            // "category_id" => $request->category,
            "priority_id" => $request->priority,
            // "for_department" => $request->for_department,
            // "from_department" => $request->from_department,
            "status_id"=> $request->status,
            "level_id"=> $request->level,
            // 'for_user'=> $request->for_user,
        ];
        // dd($data);

        try{
            $dataString = json_encode($data);
            // dd($dataString);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket/'.$id);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);  
            $response = curl_exec($ch);
            $res = json_decode($response);
            // dd($res);
            if($res->statusCode == 400){
                $message_type = 'danger';
                $message = 'Data Ticket "'.$user->username ." - ".$user->name  .'" gagal ditambahkan.';
                return redirect()->route('task-ticket.index')->with($message_type,$message);
            }else{
                $message_type = 'success';
                $message = 'Data Ticket "'.$user->username ." - ".$user->name  .'" berhasil ditambahkan.';
                return redirect()->route('task-ticket.index')->with($message_type,$message);
            }
            
        } catch (\Exception $e) {
            $message_type = 'danger';
            $message = 'Data Ticket "'.$user->username ." - ".$user->name  .'" gagal ditambahkan.';
            return redirect()->route('task-ticket.index')->with($message_type,$message);
        }
        
    }

    public function showTask($id)
    {   
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer '.$user->token,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket/all?user='.$user);
        curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket?id='.$id);
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');
        // curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
        $response = curl_exec($ch);
        $res = json_decode($response);

        if(property_exists($res, 'statusCode')){
            if($res->statusCode == 401){
                $res = null;
            }else if($res->statusCode == 500){
                $res = null;
            }
        }
        if($res == null){
            $message_type = 'danger';
            $message = 'Fitur Ticket tidak dapat diakses, coba gunakan kembali setelah beberapa saat lagi.';
            return redirect()->route('dashboard')->with($message_type,$message);
        }
        return view('ticket.taskshow')->with('ticket', $res->data)->with('user',$user)->with('page','task_ticket');
    }

}
