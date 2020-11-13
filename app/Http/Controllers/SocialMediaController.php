<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SocialMedia;

class SocialMediaController extends Controller
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
        return view('socialmedia.index')->with('page','social_media');
    }

    public function store(Request $request)
    {   
        // dd($request->all());
        request()->validate([
            'instagram' => 'required',
        ]);
        $instagram = SocialMedia::where('type','instagram')->first();
        $instagram->url = $request->instagram;
        $instagram->save();

        $facebook = SocialMedia::where('type','facebook')->first();
        $facebook->url = $request->facebook;
        $facebook->save();

        $tiktok = SocialMedia::where('type','tiktok')->first();
        $tiktok->url = $request->tiktok;
        $tiktok->save();

        return redirect()->back()->with('success','Data Social Media berhasil disimpan.');
    }
}
