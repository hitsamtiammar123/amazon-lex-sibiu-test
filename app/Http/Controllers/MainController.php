<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Event;

class MainController extends Controller
{
    //

    public function index(){
        return view('welcome');
    }

    public function test(){
        return Inertia::render('index', [
            'data' => 'this is data'
        ]);
    }
}
