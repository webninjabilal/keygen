<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\MachineGenerateCodeRequest;
use App\Http\Requests\MachineRequest;
use App\Http\Requests\UnitCartRequest;
use App\Http\Requests\UserRequest;
use App\Machine;
use App\MachineUserCode;
use App\Role;
use App\Sheet;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $user = '';
    protected $company = '';

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->company = Company::findOrFail(getSelectedCompany());
            return $next($request);
        });

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company_list = Company::orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        $is_customer = false;
        $is_user  = true;
        $machine_list = [];
        return view('user.index', compact('company_list', 'is_customer', 'is_user', 'machine_list'));
    }

    public function getCustomer()
    {
        $is_customer = true;
        $is_user  = false;
        $machine_list = $this->company->machine()->get();
        return view('user.index', compact('is_customer', 'is_user', 'machine_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $email = $request->input('email');
        $checkUser = ($email != '') ? User::where('email', $email)->first() : false;
        $activator = str_random(8);
        $user_name = '';
        if($request->has('user_name')) {
            if(!$this->checkUserName($request->input('user_name'))) {
                $this->validate($request, [
                    'user_name' => 'unique:users'
                ]);
                flash()->error('User Name must be unique');
                return json_encode(['success' => false]);
            }
            $user_name = $request->input('user_name');
        }

        if (!$checkUser or $email == '') {

            $creds = $request->only('first_name', 'last_name', 'email', 'company', 'gender', 'phone');
            $creds['password'] = bcrypt($request->input('password'));
            $user = new User();
            $user->first_name   = $creds['first_name'];
            $user->last_name    = $creds['last_name'];
            $user->email        = $creds['email'];
            $user->phone        = $creds['phone'];
            $user->password     = $creds['password'];
            $user->user_name    = $user_name;
            $user->created_by   = $this->user->id;
            $user->save();
            /*$company_id = $request->input('company_id');
            $company = Company::findOrFail($company_id);*/
            $this->company->assignContact($user, []);
        } else {
            $user = $checkUser;
        }
        if($this->user->isCustomer()) {
            $role_id = User::getUserRoleId($this->user->id);
            $user->customer_id = $this->user->customer_id;
            $user->update();
        } else if($this->user->isAdmin()){
            $role_id = $request->input('role_id');
        }
        if(isset($role_id) && !User::getUserRoleId($user->id)) {
            $user->assignRole($role_id);
        }

        if($request->has('customer_id')) {
            $user->customer_id = $request->input('customer_id');
            $user->update();
        }

        flash()->success('User added Successfully!');
        return json_encode(array('success' => true));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $company_list = Company::orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        $machine_list = [];
        if($user->isCustomer()) {
            $machine_list = $this->company->machine()->get();
        }
        return view('user.form._form', compact('user', 'company_list', 'machine_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user_id = $request->input('user_id');
        $user = User::whereHas('company', function($query) {
            $query->where('company_id', $this->company->id);
        })->findOrFail($user_id);
        $creds = $request->only('first_name', 'last_name', 'email', 'company', 'gender', 'phone');


        if($request->has('user_name')) {
            if(!$this->checkUserName($request->input('user_name'), $user)) {
                $this->validate($request, [
                    'user_name' => 'unique:users'
                ]);
                flash()->error('User Name must be unique');
                return json_encode(['success' => false]);
            }
            $creds['user_name'] = $request->input('user_name');
        }

        $user->update($creds);
        if($request->has('password') and !empty($request->input('password'))) {
            $user->password = bcrypt($request->input('password'));
            $user->update();
        }

        $role_id = $request->input('role_id');
        if(!empty($role_id)) {
            $already_role = User::getUserRoleId($user->id);
            if (!$already_role || $already_role != $role_id and !empty($role_id)) {
                $user->assignRole($role_id);
            }
        }
        /*$machine_ids = $request->input('machine_id');
        if($user->isCustomer()) {
            $this->user_machines_attach($user, $machine_ids);
        }*/
        flash()->success('User updated successfully');
        return json_encode(['success' => true]);
    }

    private function user_machines_attach($user, $machine_ids)
    {
        $user->machine_user()->whereNotIn('machine_id', $machine_ids)->delete();
        if(count($machine_ids) > 0) {
            foreach ($machine_ids as $machine_id) {
                $checkMachine = $user->machine_user()->where('machine_id',$machine_id)->first();
                if(!$checkMachine) {
                    $user->machine_user()->create([
                        'machine_id' => $machine_id
                    ]);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $user_id = $request->input('user_id');
        $user = User::whereHas('company', function($query) {
            $query->where('company_id', $this->company->id);
        })->findOrFail($user_id);
        $user->delete();
        flash()->success('User Deleted Successfully');
        return json_encode(['success' => true]);
    }


    public function records(Request $request)
    {

        $search = $request->input('search');
        $search = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');

        $filter = $request->input('columns');
        $filter_type = $filter[0]['search']['value'];
        $customer_id = $filter[1]['search']['value'];

        $order      = $request->input('order');
        $order_col  = $order[0]['column'];
        $orderby    = $order[0]['dir'];


        $query = User::whereHas('company', function($query) {
            $query->where('company_id', $this->company->id);
        })->where('email', '!=', '');
        if(!$this->user->isAdmin()) {
            $query->where('created_by', $this->user->id);
        }

        $role_ids = [];
        if($filter_type == 'customer') {
            if(!$this->user->isAdmin()) {
                $user_role_id = User::getUserRoleId($this->user->id);
                if($user_role_id) {
                    $role_ids = [User::getUserRoleId($this->user->id)];
                }
            } else {
                $role_ids = Role::where('name', 'Customer')->pluck('id')->toArray();
            }
            $query->whereHas('roles', function($query) use ($role_ids) {
                $query->whereIn('role_id', $role_ids);
            });

        } elseif($filter_type == 'admin') {
            if($this->user->isAdmin()) {
                $role_ids = Role::where('name', 'Admin')->pluck('id')->toArray();
                $query->whereHas('roles', function($query) use ($role_ids) {
                    $query->whereIn('role_id', $role_ids);
                });
            }
        }

        if(!empty($customer_id)) {
            $query->where('customer_id', $customer_id);
        }

        if ($search != '') {
            $query->where(function ($inner) use ($search){
                $inner->orWhere('first_name', 'like', '%' . $search . '%');
                $inner->orWhere('last_name', 'like', '%' . $search . '%');
                $inner->orWhere('email', 'like', '%' . $search . '%');
                $inner->orWhere('phone', 'like', '%' . $search . '%');
            });
        }


        $query->orderBy('id', 'DESC');

        $total_records = $query->count();

        $users = $query->limit($limit)->offset($start)->get();
        $records = [];

        if($users) {
            foreach($users AS $user) {
                $data = [
                    $user->first_name,
                    $user->last_name,
                    $user->email,
                ];
                if($this->user->isAdmin()) {
                    $data[3] = User::getUserRoleName($user->id);
                    $data[4] = '<span class="label label-primary">Active</span>';
                    $data[5] = '<a href="javascript:void(0)" class="btn btn-white btn-sm" onclick="getUser(' . $user->id . ')"><i class="fa fa-pencil"></i> Edit</a>
                     <a href="javascript:void(0)" class="btn btn-white btn-sm" onclick="deleteUser(' . $user->id . ')"><i class="fa fa-trash-o"></i> Delete</a>
                    ';
                }
                if($this->user->isCustomer()) {
                    $data[3] = '<span class="label label-primary">Active</span>';
                    $data[4] = '<a href="javascript:void(0)" class="btn btn-white btn-sm" onclick="getUser(' . $user->id . ')"><i class="fa fa-pencil"></i> Edit</a>
                     <a href="javascript:void(0)" class="btn btn-white btn-sm" onclick="deleteUser(' . $user->id . ')"><i class="fa fa-trash-o"></i> Delete</a>
                    ';
                }

                $records[] = $data;
            }
        }
        return [
            "sEcho" => 0,
            "recordsTotal" => $total_records,
            "recordsFiltered" => $total_records,
            "aaData" => $records,
        ];
    }

    private function checkUserName($user_name, $user = false)
    {
        $query = User::where('id', '>', 0);
        if($user) {
            $query->where('id', '!=', $user->id);
        }
        $check  = $query->where('user_name', $user_name)->first();
        if($check) {
            return false;
        }
        return true;
    }

    public function getMachineCode()
    {
        $machine        = $this->company->machine()->where('id', 1)->first();
        $user_machine   = $this->user->machine_user()->where('machine_id', 1)->first();
        if($machine and $user_machine) {
            $used_date = $user_machine->used_date;
            echo $machine_code = $machine->generate_code($user_machine->used_date, $user_machine->serial_number, $user_machine->uses);
        }
    }

    public function userMachineCodes()
    {
        $user               = $this->user;
        $customer           = $user->customer;
        $machines           = $this->company->machine()->whereIn('id', $customer->machine()->pluck('machine_id')->toArray());
        $machine_list       = $machines->pluck('nick_name', 'id')->toArray();
        $user_machine_codes = MachineUserCode::whereIn('machine_user_id', $customer->machine()->pluck('id')->toArray())->orderBy('created_at', 'desc')->where('created_by', $user->id)->paginate(25);
        return view('user.machine_codes', compact('user', 'customer', 'machine_list', 'user_machine_codes'));
    }

    public function postMachineGenerateCode(MachineGenerateCodeRequest $request)
    {
        $customer       = $this->user->customer;
        $serial_number  = $request->input('serial_number');
        $uses           = $request->input('uses');
        $machine_id     = $request->input('machine_id');
        $used_date      = $request->input('used_date');
        $used_date      = (!empty($used_date)) ? Carbon::createFromFormat('m/d/Y', $used_date)->format('Y-m-d') : date('Y-m-d');
        $machine        = $this->company->machine()->where('id', $machine_id)->first();
        $user_machine   = $customer->machine()->where('machine_id', $machine_id)->first();
        if($machine and $user_machine) {

            if($user_machine->allow_generate_code != 1) {
                return json_encode(['success' => false, 'errors' => 'Sorry, Machine has stopped to generate the code.']);
            }
            if($uses > $user_machine->credits) {
                return json_encode(['success' => false, 'errors' => 'You don’t have enough Use Credits to generate this code. Please contact Erchonia']);
            }
            $machine_code = $machine->generate_code($used_date, $serial_number, $uses);
            if(!$machine_code) {
                flash()->error('Code is not generated');
                return json_encode(['success' => false, 'errors' => 'Code is not generated, please try it again with different serial number']);
            }
            if($machine_code and !empty($machine_code)) {
                $user_machine->code()->create([
                    'serial_number' => $serial_number,
                    'used_date'     => $used_date,
                    'uses'          => $uses,
                    'code'          => $machine_code,
                    'status'        => 1,
                    'created_by'    => $this->user->id,
                    'machine_id'    => $machine->id
                ]);

                $user_machine->credits = $user_machine->credits - $uses;
                $user_machine->update();

                flash()->success('Code is generated successfully.');
                return json_encode(['success' => true]);
                //$checkCodeAlready = $user_machine->code()->where('code', $machine_code)->first();
            }
        }
        return json_encode(['success' => false, 'errors' => 'Oops, somethings went wrong']);
    }

    public function postCreateMachine(MachineRequest $request)
    {
        $serial_number = $request->input('serial_number');
        $sheet_id = $request->input('sheet_id');
        $machine = new Machine();
        if(!$machine->checkSerialAvail($serial_number, $sheet_id)) {
            return json_encode(['success' => false, 'errors' => 'Serial Number is not available please try it with different one.']);
        }
        $input = $request->except('_token');
        $input['is_time_base'] = (isset($input['is_time_base'])) ? 1 : 0;
        $input['company_id'] = $this->company->id;
        $machine = $this->user->machine()->create($input);
        flash()->success('Machine has been added successfully');
        return json_encode(['success' => true]);
    }

    public function postUpdateMachine(MachineRequest $request)
    {
        $machine_id = $request->input('machine_id');
        $machine = $this->user->machine()->where('id', $machine_id)->first();
        if($machine) {
            $serial_number = $request->input('serial_number');
            $sheet_id = $request->input('sheet_id');
            if(!$machine->checkSerialAvail($serial_number, $sheet_id)) {
                return json_encode(['success' => false, 'errors' => 'Serial Number is not available please try it with different one.']);
            }
            $input = $request->except('_token', 'machine_id');
            $input['is_time_base'] = (isset($input['is_time_base'])) ? 1 : 0;
            $machine->update($input);
            flash()->success('Machine has been updated successfully');
            return json_encode(['success' => true]);
        }
        return json_encode(['success' => true, 'errors' => 'Machine has not found']);
    }

    public function getUpdateMachine($machine_id)
    {
        $machine = $this->user->machine()->where('id', $machine_id)->first();
        if($machine) {
            $sheets = Sheet::orderBy('id', 'desc')->pluck('title', 'id')->toArray();
            return view('user.form._form_machine', compact('machine', 'sheets'));
        }
        return 'No record found';
    }

    public function postDeleteMachine(Request $request)
    {
        $machine_id = $request->input('machine_id');
        $machine = $this->user->machine()->where('id', $machine_id)->first();
        if($machine) {
            $machine->delete();
            flash()->success('Machine has been deleted successfully');
            return json_encode(['success' => true]);
        }
        return json_encode(['success' => true, 'errors' => 'Machine has not found']);
    }

    public function getPurchaseUnits()
    {
        $units  = $this->company->unit()->active()->get();
        return view('unit.purchase', compact('units'));
    }

    public function getPurchaseUnit(Request $request, $unit_id)
    {
        //$unit_id = $request->input('unit_id');
        $unit  = $this->company->unit()->where('id', $unit_id)->first();
        if($unit) {
            return view('unit._form_purchase', compact('unit'));
        }
        return 'Not found';
    }

    public function postAddCartUnit(UnitCartRequest $request)
    {
        $unit_id = $request->input('unit_id');
        $unit  = $this->company->unit()->active()->where('id', $unit_id)->first();
        if($unit) {
            $machine_date = $request->input('machine_date');
            $machine_date = (!empty($machine_date)) ? Carbon::createFromFormat('m/d/Y', $machine_date)->format('Y-m-d') : date('Y-m-d');
            $this->user->unit_order()->create([
                'company_id' => $this->company->id,
                'unit_id' => $unit->id,
                'machine_date' => $machine_date,
                'filter_type' => $request->input('filter_type'),
                'machine_id' => $request->input('machine_id'),
                'quantity' => $request->input('quantity'),
                'status' => 1,
                'billing_id' => 0,
                'license_key' => time(),
                'server_ip' => $request->getClientIp(),
            ]);
            flash()->success('Order has been added successfully');
            return json_encode(['success' => true]);
        }
        return json_encode(['success' => true, 'errors' => 'Sorry, no units are available at this time for purchase. ']);
    }

    public function getUserOrder($order_id)
    {
        $order = $this->user->unit_order()->where('id', $order_id)->first();
        if($order) {
            return view('unit.order_view', compact('order'));
        }
        return 'Not found';
    }

}
