<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class frontController extends Controller
{
    public function home(){
        return view('front.index');
    }
    public function contact(){
        return view('front.pages.contact');
    }

    public function pricing(){
        return view('front.pages.pricing');
    }

    public function developers(){
        return view('front.pages.dedicated_developers');
    }

    public function about(){
        return view('front.pages.company.about');
    }

    public function blog(){
        return view('front.pages.company.blog');
    }

    public function  portfolio(){
        return view('front.pages.company.portfolio');
    }

    public function  blockChain(){
        return view('front.pages.services.blackChain');
    }

    public function  ecommerce(){
        return view('front.pages.industry.ecommerce');
    }

}
