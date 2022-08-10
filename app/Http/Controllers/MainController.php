<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Event;
use App\Models\Person;
use Illuminate\Support\Facades\Redirect;
use Aws\LexRuntimeService\LexRuntimeServiceClient;

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

    public function chat(Request $request){
        $lex_client = new LexRuntimeServiceClient([
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ],
            'region' => 'ap-southeast-1',
            'version' => '2016-11-28',
            'http' => [
                'verify' => false
            ]
        ]);
        $result = $lex_client->postText([
            'botAlias' => '$LATEST',
            'botName' => 'BookTrip_dev',
            'inputText' => $request->input('inputText', ''),
            'userId' => $request->input('userId', 'user-1234-56788')

        ]);
        return [
            'message' => $result['message'],
            'dialogState' => $result['dialogState']
        ];
    }

}
