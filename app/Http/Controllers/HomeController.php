<?php

namespace App\Http\Controllers;

use App\Company;
use App\Sheet;
use Illuminate\Http\Request;
use Auth;


class HomeController extends Controller
{
    protected $user = '';
    protected $company = '';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->company = Company::findOrFail(getSelectedCompany());
            return $next($request);
        });
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
        $sheets = $this->company->sheet()->orderBy('id', 'desc')->pluck('title', 'id')->toArray();
        $orders = $user->unit_order()->paginate(25);
        return view('user.my-account', compact('user', 'machines', 'sheets', 'orders'));
    }
}
