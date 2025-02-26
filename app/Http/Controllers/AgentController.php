<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    // public function messages(){
    //     $messages = Messages::all();
    //     return  view('agent.message', compact('messages'));
    // }

    public function pharmacies(){
        $pharmacies = Pharmacy::where('agent_id', Auth::user()->id)->get();
        return  view('agent.pharmacies', compact('pharmacies'));
    }
}
