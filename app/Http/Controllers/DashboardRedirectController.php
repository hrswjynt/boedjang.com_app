<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    public function __invoke()
    {
        return redirect('dashboard');
    }
}
