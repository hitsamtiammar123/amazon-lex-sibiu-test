<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Event;
use App\Models\Person;
use Illuminate\Support\Facades\Redirect;

class MainController extends Controller
{
    //

    public function index(){
        return Inertia::render('index');
    }

    public function store(Request $request){
        $request->validate([
            'email' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'chatlist' => 'required'
        ]);
        try{
            $person = Person::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email
            ]);
            $person->chats()->createMany($request->chatlist);
        }catch(Exception $err){

        }
        return redirect('/');
    }
}
