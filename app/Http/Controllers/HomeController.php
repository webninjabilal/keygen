<?php

namespace App\Http\Controllers;

use App\Company;
use App\MachineUserCode;
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
        /*$machines = $user->machine()->paginate(25);*/
        $machines           = $this->company->machine()->whereIn('id', $user->machine_user()->pluck('machine_id')->toArray());
        $machine_list       = $machines->pluck('nick_name', 'id')->toArray();
        $user_machine_codes = MachineUserCode::whereIn('machine_user_id', $user->machine_user()->pluck('id')->toArray())->orderBy('created_at', 'desc')->paginate(25);
        $sheets             = $this->company->sheet()->orderBy('id', 'desc')->pluck('title', 'id')->toArray();
        $orders             = $user->unit_order()->paginate(25);
        return view('user.my-account', compact('user', 'user_machine_codes', 'sheets', 'orders', 'machine_list'));
    }
}
