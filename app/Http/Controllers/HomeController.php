<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return view('welcome');
    }

    public function search(){
        return view('search');
    }

    public function dashboard(){
        return view('dashboard');
    }

    public function gymDetails(){
        return view('gym-details');
    }

    public function signup(){
        return view('signup');
    }

    public function login(){
        return view('login');
    }
}
