<?php

namespace App\Http\Controllers;

use App\Sheet;
use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    public function my_account()
    {
        $user = Auth::user();
        $machines = $user->machine()->paginate(25);
        $sheets = Sheet::orderBy('id', 'desc')->pluck('title', 'id')->toArray();
        $orders = $user->unit_order()->paginate(25);
        return view('user.my-account', compact('user', 'machines', 'sheets', 'orders'));
    }
}
