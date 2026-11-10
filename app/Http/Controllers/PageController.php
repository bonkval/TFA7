<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'title' => 'POS Lab | Home',
            'heading' => 'Point-of-Sale System',
        ]);
    }

    public function about(): View
    {
        return view('pages.about', [
            'title' => 'POS Lab | About',
            'heading' => 'About This Activity',
        ]);
    }
}
