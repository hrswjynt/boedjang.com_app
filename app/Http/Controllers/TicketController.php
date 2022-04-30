<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Karyawan;
use App\User;
use DataTables;
use Auth;
use DB;
use File;
use Image;

class TicketController extends Controller
{
    private $url = "https://api-tiket.sabangdigital.id";
    // private $url = "http://10.10.9.3:1438";
    private $token = "K6LA/bKakT5cpTcg65MBs.d/7/4WTLm6bhUnEgniLT6q5MrNNS3Pa";

    function __construct()
    {
    }

    public function pengajuan()
    {
        $karyawan = Karyawan::where('NIP', Auth::user()->username)->first();
        // $headers = [
        //     'Authorization: Bearer ' . Auth::user()->token . '',
        //     'Content-Type: application/json',
        // ];

        $client = new \GuzzleHttp\Client();
        $requestClient = $client->get($this->url . '/ticket/details', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . Auth::user()->token
            ]
        ]);
        $res = json_decode($requestClient->getBody()->getContents());

        $data = $res->data;
        if ($res !== null) {
            $department = $data->departments;
            $platform = $data->platforms;
            $category = $data->categories;
            $priority = $data->priorities;
        }
        return view('ticket.pengajuan')->with('page', 'ticket')
            ->with('karyawan', $karyawan)
            ->with('department', $department)
            ->with('category', $category)
            ->with('priority', $priority)
            ->with('platform', $platform);
    }

    public function pengajuanpost(Request $request)
    {
        // dd($request->all());
        $fields = \request()->all();

        $output = [];
        if(count($request->files) > 0){
            foreach ($fields['files'] as $key => $value) {
                if (!is_array($value)) {
                    $output[] = [
                        'name'     => 'files',
                        'contents' => fopen($value->getPathname(), 'r'),
                        'filename' => $value->getClientOriginalName()
                    ];
                    continue;
                }

                foreach ($value as $multiKey => $multiValue) {
                    $multiName = $key . '[' . $multiKey . ']' . (is_array($multiValue) ? '[' . key($multiValue) . ']' : '') . '';
                    $output[]  = [
                        'name'     => 'files',
                        'contents' => (is_array($multiValue) ? reset($multiValue) : $multiValue)
                    ];
                }
            }
        }

        // dd($output);

        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer ' . $user->token,
            'Content-Type: application/json',
        ];

        $data = [
            [
                'name'     => 'code',
                'contents' => 'SABI#' . date('dmyHis')
            ],
            [
                'name'     => 'title',
                'contents' => $request->title
            ],
            [
                'name'     => 'description',
                'contents' => $request->description
            ],
            [
                'name'     => 'platform_id',
                'contents' => 99
            ],
            [
                'name'     => 'category_id',
                'contents' => 99
            ],
            [
                'name'     => 'priority_id',
                'contents' => $request->priority,
            ],
            [
                'name'     => 'for_department',
                'contents' => $request->for_department
            ],
            [
                'name'     => 'from_department',
                'contents' => $user->ticket_department
            ],
        ];
        foreach ($output as $out) {
            $data[] = $out;
        }
        // dd($data);

        $client = new \GuzzleHttp\Client();
        $requestClient = $client->request('POST', $this->url . '/ticket', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . Auth::user()->token
            ],
            // 'form_params' => ($data),
            'multipart' => $data
        ]);
        // dd($requestClient);
        $res = json_decode($requestClient->getBody()->getContents());
        // dd($res);

        // $image = $request->file('gambar');
        //     $image_name = time() . 'sop.' . $image->getClientOriginalExtension();
        //     $destinationPath = public_path('/images/sop');
        //     $resize_image = Image::make($image->getRealPath());
        //     $resize_image->resize(null, 300, function($constraint){
        //         $constraint->aspectRatio();
        // })->save($destinationPath . '/' . $image_name);

        // $i = 0;
        // $array_file = [];
        // foreach($request->files as $f){
        //     $image = ($request->file('files')[$i]);
        //     // return $image;
        //     // return $image->getClientOriginalName();
        //     // return $image->getRealPath();
        //     $cFile = curl_file_create($image->getRealPath(), 'image/'.$image->getClientOriginalExtension(), $image->getClientOriginalName());
        //     array_push($array_file, $cFile);
        //     $i++;
        // }
        // dd($array_file);


        try {
            // $dataString = json_encode($data);
            // $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL, $this->url . '/ticket');
            // curl_setopt($ch, CURLOPT_POST, true);
            // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            // $response = curl_exec($ch);
            // $res = json_decode($response);
            // dd($res);
            if ($res->statusCode == 400) {
                $message_type = 'danger';
                $message = 'Data Ticket "' . $user->username . " - " . $user->name  . '" gagal ditambahkan.';
                return redirect()->route('ticket.index')->with($message_type, $message);
            } else {
                $message_type = 'success';
                $message = 'Data Ticket "' . $user->username . " - " . $user->name  . '" berhasil ditambahkan.';
                return redirect()->route('ticket.index')->with($message_type, $message);
            }
        } catch (\Exception $e) {
            $message_type = 'danger';
            $message = 'Data Ticket "' . $user->username . " - " . $user->name  . '" gagal ditambahkan.';
            return redirect()->route('ticket.index')->with($message_type, $message);
        }
    }

    public function index()
    {
        return view('ticket.index')->with('page', 'ticket');
    }

    public function getData()
    {
        // dd('tes');
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer ' . $user->token,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket/all?user='.$user);
        curl_setopt($ch, CURLOPT_URL, $this->url . '/ticket/all?from-currentuser');
        // curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket/all?from-department=2');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        // curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
        $response = curl_exec($ch);
        $res = json_decode($response);
        // dd($res);
        if (property_exists($res, 'statusCode')) {
            if ($res->statusCode == 401) {
                $table = [];
            } else if ($res->statusCode == 500) {
                $table = [];
            } else {
                $table = $res->data;
            }
        } else if ($res == null) {
            $table = [];
        }


        // dd($res);
        return Datatables::of($table)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-info btn-simple shadow" href="' . route("ticket.show", $data->id) . '" title="Info"><i class="fa fa-search"></i>Info</a>';
                $action .= '</div>';
                return $action;
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function delete($id)
    {
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer ' . $user->token,
            'Content-Type: application/json',
        ];
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url . '/ticket/' . $id);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $res = json_decode($response);
            // dd($res);
            if (property_exists($res, 'statusCode')) {
                if ($res->statusCode == 200) {
                    return response()->json([
                        'message' => 'Ticket "' . $id . '" berhasil dihapus!',
                        'type' => 'success',
                    ]);
                } else {
                    return response()->json([
                        'message' => $res->message,
                        'type' => 'danger',
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ticket "' . $id . '" gagal dihapus!',
                'type' => 'danger',
            ]);
        }
    }

    public function show($id)
    {
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer ' . $user->token,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket/all?user='.$user);
        curl_setopt($ch, CURLOPT_URL, $this->url . '/ticket?id=' . $id);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        // curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
        $response = curl_exec($ch);
        $res = json_decode($response);

        if (property_exists($res, 'statusCode')) {
            if ($res->statusCode == 401) {
                $res = null;
            } else if ($res->statusCode == 500) {
                $res = null;
            }
        }
        if ($res == null) {
            $message_type = 'danger';
            $message = 'Fitur Ticket tidak dapat diakses, coba gunakan kembali setelah beberapa saat lagi.';
            return redirect()->route('dashboard')->with($message_type, $message);
        }
        return view('ticket.pengajuanshow')->with('ticket', $res->data)->with('user', $user)->with('page', 'ticket');
    }

    public function manajemenTicket()
    {
        $user = User::find(Auth::user()->id);
        return view('ticket.manajementicket')->with('page', 'manajementicket')->with('user', $user);
    }

    public function getDataManajemenTicket()
    {
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer ' . $user->token,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url . '/ticket/all?from-department=' . $user->ticket_department);
        // curl_setopt($ch, CURLOPT_URL, $this->url . '/ticket/get-all');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $res = json_decode($response);
        // dd($res);
        if (property_exists($res, 'statusCode')) {
            if ($res->statusCode == 200 || $res->statusCode == 302) {
                $table = $res->data;
                $table = $res->data;
            } else {
                $table = [];
            }
        } else if ($res == null) {
            $table = [];
        }


        // dd($res);
        return Datatables::of($table)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-info btn-simple shadow" href="' . route("ticket.show", $data->id) . '" title="Info"><i class="fa fa-search"></i>Info</a>';
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("ticket.edit", $data->id) . '" title="Edit"><i class="fa fa-edit"></i> Manage</a>';
                $action .= '<a href="' . route("ticket.delete", $data->id) . '" class="btn btn-sm btn-danger btn-simple shadow ticketDelete" title="Delete" data-id="' . $data->id . '"><i class="fa fa-trash"></i> Delete</a>';
                $action .= '</div>';
                return $action;
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function manajemenTicketDepart()
    {
        $user = User::find(Auth::user()->id);
        return view('ticket.manajementicketdepart')->with('page', 'manajementicketdepart')->with('user', $user);
    }

    public function getDataManajemenTicketDepart()
    {
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer ' . $user->token,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url . '/ticket/all?for-department=' . $user->ticket_department);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $res = json_decode($response);
        // dd($res);
        if (property_exists($res, 'statusCode')) {
            if ($res->statusCode == 200 || $res->statusCode == 302) {
                $table = $res->data;
                $table = $res->data;
            } else {
                $table = [];
            }
        } else if ($res == null) {
            $table = [];
        }


        // dd($res);
        return Datatables::of($table)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-info btn-simple shadow" href="' . route("ticket.show", $data->id) . '" title="Info"><i class="fa fa-search"></i>Info</a>';
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("ticket.edit", $data->id) . '" title="Edit"><i class="fa fa-edit"></i> Manage</a>';
                $action .= '<a href="' . route("ticket.delete", $data->id) . '" class="btn btn-sm btn-danger btn-simple shadow ticketDelete" title="Delete" data-id="' . $data->id . '"><i class="fa fa-trash"></i> Delete</a>';
                $action .= '</div>';
                return $action;
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function edit($id)
    {
        $karyawan = Karyawan::where('NIP', Auth::user()->username)->first();
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer ' . Auth::user()->token . '',
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url . '/ticket/details');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response1 = curl_exec($ch);
        $res = json_decode($response1);

        curl_setopt($ch, CURLOPT_URL, $this->url . '/ticket?id=' . $id);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response2 = curl_exec($ch);
        $ticket = json_decode($response2);

        // dd($ticket);
        // dd($res);
        if (property_exists($res, 'statusCode')) {
            if ($res->statusCode == 401) {
                $res = null;
            } else if ($res->statusCode == 500) {
                $res = null;
            }
        }
        $data = $res->data;
        if ($res !== null) {
            $department = $data->departments;
            $platform = $data->platforms;
            $category = $data->categories;
            $priority = $data->priorities;
            $status = $data->statuses;
            $level = $data->levels;
            $userdata = $data->users;
            $attachment = $ticket->data->attachments;
        }

        // dd($attachment);

        if (property_exists($ticket, 'statusCode')) {
            if ($ticket->statusCode == 401) {
                $ticket = null;
            } else if ($ticket->statusCode == 500) {
                $ticket = null;
            }
        }

        if ($res !== null) {
            $nip = $ticket->data->from_users->username;
            $user = Karyawan::where("NIP", $nip)->first();
        } else {
            $user = null;
        }
        if ($user == null) {
            $message_type = 'danger';
            $message = 'Data Pengguna Ticket tidak ditemukan.';
            return redirect()->route('manajementicket.index')->with($message_type, $message);
        }

        // dd($department);
        return view('ticket.pengajuanedit')->with('page', 'manajementicket')
            ->with('ticket', $ticket->data)
            ->with('user', $user)
            ->with('userdata', $userdata)
            ->with('karyawan', $karyawan)
            ->with('department', $department)
            ->with('category', $category)
            ->with('priority', $priority)
            ->with('status', $status)
            ->with('level', $level)
            ->with('attachment', $attachment)
            ->with('platform', $platform);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        // dd($id);
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer ' . $user->token,
            'Content-Type: application/json',
        ];
        if(Auth::user()->ticket_role === 1){
            $data = [
                "title" => $request->title,
                "description" => $request->description,
                "platform_id" => $request->platform,
                "category_id" => $request->category,
                "priority_id" => $request->priority,
                "for_department" => $request->for_department,
                "from_department" => $request->from_department,
                "status_id" => $request->status,
                "level_id" => $request->level,
                'for_user' => $request->for_user,
            ];
        }else{
            $data = [
                "platform_id" => $request->platform,
                "category_id" => $request->category,
                "priority_id" => $request->priority,
                "status_id" => $request->status,
                "level_id" => $request->level,
                'for_user' => $request->for_user,
            ];
        }
        


        try {
            $dataString = json_encode($data);
            // dd($dataString);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url . '/ticket/' . $id);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $response = curl_exec($ch);
            $res = json_decode($response);
            // dd($res);
            if ($res->statusCode == 400) {
                $message_type = 'danger';
                $message = 'Data Ticket "' . $user->username . " - " . $user->name  . '" gagal ditambahkan.';
                return redirect()->route('manajementicket.index')->with($message_type, $message);
            } else {
                $message_type = 'success';
                $message = 'Data Ticket "' . $user->username . " - " . $user->name  . '" berhasil ditambahkan.';
                return redirect()->route('manajementicket.index')->with($message_type, $message);
            }
        } catch (\Exception $e) {
            $message_type = 'danger';
            $message = 'Data Ticket "' . $user->username . " - " . $user->name  . '" gagal ditambahkan.';
            return redirect()->route('manajementicket.index')->with($message_type, $message);
        }
    }

    public function indexTask()
    {
        return view('ticket.indextask')->with('page', 'task_ticket');
    }

    public function getDataTask()
    {
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer ' . $user->token,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket/all?user='.$user);
        curl_setopt($ch, CURLOPT_URL, $this->url . '/ticket/all?for-currentuser');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        // curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
        $response = curl_exec($ch);
        $res = json_decode($response);
        // dd($res);
        if (property_exists($res, 'statusCode')) {
            if ($res->statusCode == 401) {
                $table = [];
            } else if ($res->statusCode == 500) {
                $table = [];
            } else {
                $table = $res->data;
            }
        } else if ($res == null) {
            $table = [];
        }


        // dd($res);
        return Datatables::of($table)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">';
                $action .= '<a class="btn btn-sm btn-info btn-simple shadow" href="' . route("task-ticket.show", $data->id) . '" title="Info"><i class="fa fa-search"></i>Info</a>';
                $action .= '<a class="btn btn-sm btn-warning btn-simple shadow" href="' . route("task-ticket.edit", $data->id) . '" title="Edit"><i class="fa fa-edit"></i> Manage</a>';
                $action .= '</div>';
                return $action;
            })
            ->addIndexColumn()
            ->make(true);
    }


    public function editTask($id)
    {
        $karyawan = Karyawan::where('NIP', Auth::user()->username)->first();
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer ' . Auth::user()->token . '',
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url . '/ticket/details');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response1 = curl_exec($ch);
        $res = json_decode($response1);

        curl_setopt($ch, CURLOPT_URL, $this->url . '/ticket?id=' . $id);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response2 = curl_exec($ch);
        $ticket = json_decode($response2);

        // dd($ticket);
        // dd($res);
        if (property_exists($res, 'statusCode')) {
            if ($res->statusCode == 401) {
                $res = null;
            } else if ($res->statusCode == 500) {
                $res = null;
            }
        }
        $data = $res->data;
        if ($res !== null) {
            $department = $data->departments;
            $platform = $data->platforms;
            $category = $data->categories;
            $priority = $data->priorities;
            $status = $data->statuses;
            $level = $data->levels;
            $userdata = $data->users;
            $attachment = $ticket->data->attachments;
        }

        if (property_exists($ticket, 'statusCode')) {
            if ($ticket->statusCode == 401) {
                $ticket = null;
            } else if ($ticket->statusCode == 500) {
                $ticket = null;
            }
        }

        if ($res !== null) {
            $nip = $ticket->data->from_users->username;
            $user = Karyawan::where("NIP", $nip)->first();
        } else {
            $user = null;
        }
        if ($user == null) {
            $message_type = 'danger';
            $message = 'Data Pengguna Ticket tidak ditemukan.';
            return redirect()->route('task-ticket.index')->with($message_type, $message);
        }

        // dd($department);
        return view('ticket.taskedit')->with('page', 'task_ticket')
            ->with('ticket', $ticket->data)
            ->with('user', $user)
            ->with('userdata', $userdata)
            ->with('karyawan', $karyawan)
            ->with('department', $department)
            ->with('category', $category)
            ->with('priority', $priority)
            ->with('status', $status)
            ->with('level', $level)
            ->with('attachment', $attachment)
            ->with('platform', $platform);
    }

    public function updateTask(Request $request, $id)
    {
        // dd($request->all());
        // dd($id);
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer ' . $user->token,
            'Content-Type: application/json',
        ];
        $data = [
            // "title" => $request->title,
            // "description" => $request->description,
            // "platform_id" => $request->platform,
            // "category_id" => $request->category,
            // "priority_id" => $request->priority,
            // "for_department" => $request->for_department,
            // "from_department" => $request->from_department,
            "status_id" => $request->status,
            "level_id" => $request->level,
            // 'for_user'=> $request->for_user,
        ];
        // dd($data);

        try {
            $dataString = json_encode($data);
            // dd($dataString);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url . '/ticket/' . $id);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $response = curl_exec($ch);
            $res = json_decode($response);
            // dd($res);
            if ($res->statusCode == 400) {
                $message_type = 'danger';
                $message = 'Data Ticket "' . $user->username . " - " . $user->name  . '" gagal ditambahkan.';
                return redirect()->route('task-ticket.index')->with($message_type, $message);
            } else {
                $message_type = 'success';
                $message = 'Data Ticket "' . $user->username . " - " . $user->name  . '" berhasil ditambahkan.';
                return redirect()->route('task-ticket.index')->with($message_type, $message);
            }
        } catch (\Exception $e) {
            $message_type = 'danger';
            $message = 'Data Ticket "' . $user->username . " - " . $user->name  . '" gagal ditambahkan.';
            return redirect()->route('task-ticket.index')->with($message_type, $message);
        }
    }

    public function showTask($id)
    {
        $user = User::where("username", Auth::user()->username)->first();
        $headers = [
            'Authorization: Bearer ' . $user->token,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $this->url.'/ticket/all?user='.$user);
        curl_setopt($ch, CURLOPT_URL, $this->url . '/ticket?id=' . $id);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        // curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
        $response = curl_exec($ch);
        $res = json_decode($response);

        if (property_exists($res, 'statusCode')) {
            if ($res->statusCode == 401) {
                $res = null;
            } else if ($res->statusCode == 500) {
                $res = null;
            }
        }
        if ($res == null) {
            $message_type = 'danger';
            $message = 'Fitur Ticket tidak dapat diakses, coba gunakan kembali setelah beberapa saat lagi.';
            return redirect()->route('dashboard')->with($message_type, $message);
        }
        return view('ticket.taskshow')->with('ticket', $res->data)->with('user', $user)->with('page', 'task_ticket');
    }
}
