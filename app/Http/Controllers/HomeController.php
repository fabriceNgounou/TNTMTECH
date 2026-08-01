<?php

namespace App\Http\Controllers;

use App\Models\Service;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', [
            'services' => Service::where('is_published', true)->take(6)->get(),
        ]);
    }
}
