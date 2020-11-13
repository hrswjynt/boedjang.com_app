<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Konten;
use DB;

class ContentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        return view('content.index')->with('page','content');
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        request()->validate([
            'title' => 'required',
        ]);
        $title = Konten::where('type','title')->first();
        $title->content = $request->title;
        $title->save();

        $header_menu = Konten::where('type','header_menu')->first();
        $header_menu->content = $request->header_menu;
        $header_menu->save();

        $header = Konten::where('type','header')->first();
        $header->content = $request->header;
        $header->save();

        $subheader = Konten::where('type','subheader')->first();
        $subheader->content = $request->subheader;
        $subheader->save();

        $about = Konten::where('type','about')->first();
        $about->content = $request->about;
        $about->save();

        $contact = Konten::where('type','contact')->first();
        $contact->content = $request->contact;
        $contact->save();

        $footer = Konten::where('type','footer')->first();
        $footer->content = $request->footer;
        $footer->save();

        return redirect()->back()->with('success','Data konten berhasil disimpan.');
    }
}
