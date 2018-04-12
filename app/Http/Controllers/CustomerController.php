<?php

namespace App\Http\Controllers;

use App\Company;
use App\Customer;
use App\Http\Requests\CustomerRequest;
use App\MachineUserCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
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
    public function index()
    {
        $machine_list = $this->company->machine()->get();
        return view('customer.index', compact('machine_list'));
    }


    public function getDetail($customer_id)
    {
        $customer = $this->company->customer()->where('id', $customer_id)->first();
        if($customer) {
            $is_customer = true;
            $is_user = false;
            $user_machine_codes = MachineUserCode::where(function ($inner) use ($customer) {
                $inner->orWhereIn('machine_user_id', $customer->machine()->active()->pluck('id')->toArray());
                $inner->orWhereIn('created_by', $customer->user()->pluck('id')->toArray());
            })->orderBy('created_at', 'desc')->paginate(25);

            $customer_machines = $customer->machine()->active()->get();
            return view('customer.detail', compact('customer', 'user_machine_codes', 'is_customer', 'is_user', 'customer_machines'));
        }
    }


    public function postUpdateMachineCredits(Request $request, $customer_id)
    {
        $customer = $this->company->customer()->where('id', $customer_id)->first();
        if($customer) {
            $customer_machine_id    = $request->input('value_id');
            $value                  = $request->input('value');

            $customer_machine = $customer->machine()->where('id', $customer_machine_id)->first();
            if($customer_machine) {

                $this->user->log()->create([
                    'nature' => 'machine_user_credits',
                    'object_id' => $customer_machine->id,
                    'detail' => serialize($customer_machine->toArray()),
                ]);

                $customer_machine->update(['credits' => $value]);

                if($customer_machine->credits > 200) {
                    $customer_machine->notification_email = 0;
                    $customer_machine->update();
                }
                return json_encode(['success' => true]);
            }
        }
        return json_encode(['success' => false]);
    }
    public function postMachineAllowCode(Request $request, $customer_id)
    {
        $customer = $this->company->customer()->where('id', $customer_id)->first();
        if($customer) {
            $customer_machine_id    = $request->input('customer_machine_id');
            $allow_generate_code    = $request->input('allow_generate_code');

            $customer_machine = $customer->machine()->where('id', $customer_machine_id)->first();
            if($customer_machine) {
                $customer_machine->update(['allow_generate_code' => $allow_generate_code]);
                return json_encode(['success' => true]);
            }
        }
        return json_encode(['success' => false]);
    }

    public function postMachineAllowSerialGenerateCode(Request $request, $customer_id)
    {
        $customer = $this->company->customer()->where('id', $customer_id)->first();
        if($customer) {
            $machine_user_code_id    = $request->input('machine_user_code_id');
            $allow_generate_code    = $request->input('allow_generate_code');

            $customer_machine_code = MachineUserCode::where('id', $machine_user_code_id)->first();
            if($customer_machine_code) {
                $customer_machine_code->update(['block_serial_number' => $allow_generate_code]);
                return json_encode(['success' => true]);
            }
        }
        return json_encode(['success' => false]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        $input = $request->except('_token');
        $input['created_by'] = $this->user->id;
        $customer = $this->company->customer()->create($input);
        $machine_ids = $request->input('machine_id');
        if(count($machine_ids) > 0) {
            $this->machines_attach($customer, $machine_ids);
        }
        flash()->success('Customer has been added successfully!');
        return json_encode(['success' => true]);
    }

    private function machines_attach($customer, $machine_ids)
    {
        $customer->machine()->whereNotIn('machine_id', $machine_ids)->update(['status' => 2]);
        if(count($machine_ids) > 0) {
            foreach ($machine_ids AS $machine_id) {
                $checkMachine = $customer->machine()->where('machine_id',$machine_id)->first();
                if(!$checkMachine) {
                    $customer->machine()->create([
                        'machine_id' => $machine_id,
                        'status' => 1
                    ]);
                } else {
                    $checkMachine->update([
                        'status' => 1
                    ]);
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        $machine_list = $this->company->machine()->get();
        return view('customer._form', compact('customer', 'machine_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, $id)
    {
        $customer_id  = $request->input('customer_id');
        $customer = $this->company->customer()->where('id', $customer_id)->first();
        if($customer) {
            $customer->update($request->except('_token', 'customer_id'));
            $machine_ids = $request->input('machine_id');
            if(count($machine_ids) > 0) {
                $this->machines_attach($customer, $machine_ids);
            }
            flash()->success('Customer has been updated successfully!');
            return json_encode(['success' => true]);
        }
        return json_encode(['success' => false, 'errors' => 'Something goes wrong']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $customer_id = $request->input('customer_id');
        $customer = $this->company->customer()->where('id', $customer_id)->first();
        if($customer) {
            $customer->delete();
        }

        flash()->success('Customer Deleted Successfully');
        return json_encode(['success' => true]);
    }

    public function records(Request $request)
    {

        $search = $request->input('search');
        $search = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');

        $filter = $request->input('columns');

        $order      = $request->input('order');
        $order_col  = $order[0]['column'];
        $order_by    = $order[0]['dir'];

        $query = $this->company->customer()->where('name', '!=', '')
        ->leftJoin('machine_users', 'machine_users.customer_id', '=', 'customers.id')
        ->leftJoin('machines', 'machines.id', '=', 'machine_users.machine_id');
        if ($search != '') {
            $query->where(function ($inner) use ($search){
                $inner->orWhere('customers.name', 'like', '%' . $search . '%');
                $inner->orWhere('machines.nick_name', 'like', '%' . $search . '%');
                $inner->orWhere('machine_users.credits', 'like', '%' . $search . '%');
            });
        }
        $query->select(\DB::raw('customers.id as id'),'customers.name', 'machines.nick_name as machine_name', 'machine_users.credits as machine_credits');

        /*if($order_col == 0) {
            $query->orderBy('customers.name', $order_by);
        } else if($order_col == 1) {
            $query->orderBy('machines.nick_name', $order_by);
        } else if($order_col == 2) {
            $query->orderBy('machine_users.credits', $order_by);
        }*/

        $query->orderBy('customers.id', 'DESC')->orderBy('machine_users.customer_id', 'DESC');
        $total_records = $query->get()->count();

        $customers = $query->limit($limit)->offset($start)->get();
        $records = [];

        if(count($customers) > 0) {

            $first_customer = '';
            foreach($customers AS $customer) {

                $customer_link = '';
                $action_link = '';
                if(empty($first_customer) or $first_customer != $customer->id) {
                    $first_customer = $customer->id;
                    $customer_link = '<a href="'.route('customer_detail', [$customer->id]).'">'.$customer->name.'</a>';
                    $action_link = '<a href="javascript:void(0)" class="btn btn-white btn-sm" onclick="getCustomer(' . $customer->id . ')"><i class="fa fa-pencil"></i> Edit</a>
                     <a href="javascript:void(0)" class="btn btn-white btn-sm" onclick="deleteCustomer(' . $customer->id . ')"><i class="fa fa-trash-o"></i> Delete</a>
                    ';
                }
                $data = [
                    $customer_link,
                    $customer->machine_name,
                    $customer->machine_credits,
                    $action_link
                ];
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

    public function getExportRecords(Request $request)
    {

        $search = $request->input('search');
        $heading = [
            'Customer Name',
            'Machine Type',
            'Credit Pool',
        ];

        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: attachment;filename='.time().'customer-filter-report.csv');
        $fp = fopen('php://output', 'w');

        fputcsv($fp, ['Customer Filter Report at '.date('m/d/Y h:i:s A')]);
        fputcsv($fp, ['']);
        fputcsv($fp, $heading);

        $query = $this->company->customer()->where('name', '!=', '')
            ->leftJoin('machine_users', 'machine_users.customer_id', '=', 'customers.id')
            ->leftJoin('machines', 'machines.id', '=', 'machine_users.machine_id');
        if ($search != '') {
            $query->where(function ($inner) use ($search){
                $inner->orWhere('customers.name', 'like', '%' . $search . '%');
                $inner->orWhere('machines.nick_name', 'like', '%' . $search . '%');
                $inner->orWhere('machine_users.credits', 'like', '%' . $search . '%');
            });
        }
        $query->select(\DB::raw('customers.id as id'),'customers.name', 'machines.nick_name as machine_name', 'machine_users.credits as machine_credits');
        $query->orderBy('customers.id', 'DESC')->orderBy('machine_users.customer_id', 'DESC');

        $total_records = $query->get()->count();

        $customers = $query->get();
        $records = [];

        if(count($customers) > 0) {

            $first_customer = '';
            foreach($customers AS $customer) {

                $customer_link = '';
                if(empty($first_customer) or $first_customer != $customer->id) {
                    $first_customer = $customer->id;
                    $customer_link = $customer->name;
                }
                $data = [
                    $customer_link,
                    $customer->machine_name,
                    $customer->machine_credits,
                ];
                fputcsv($fp, $data);
            }
        }
    }

    public function getExportCustomData(Request $request, $customer_id)
    {
        $customer = $this->company->customer()->where('id', $customer_id)->first();
        if($customer) {
            $type = $request->input('type');
            header( 'Content-Type: text/csv' );
            header( 'Content-Disposition: attachment;filename='.time().'customer-filter-report.csv');
            $fp = fopen('php://output', 'w');


            if($type == 'codes') {

                fputcsv($fp, [$customer->name.' Export Codes at '.date('m/d/Y h:i:s A')]);
                fputcsv($fp, ['']);
                fputcsv($fp, ['Machine Name', 'Generated User', 'Serial', 'Uses', 'Machine Date', 'Generated Code', 'Generated at']);

                $user_machine_codes = MachineUserCode::where(function ($inner) use ($customer) {
                    $inner->orWhereIn('machine_user_id', $customer->machine()->active()->pluck('id')->toArray());
                    $inner->orWhereIn('created_by', $customer->user()->pluck('id')->toArray());
                })->orderBy('created_at', 'desc')->get();

                if(count($user_machine_codes) > 0) {
                    foreach($user_machine_codes AS $user_machine_code){
                        $data =  [];
                        if(isset($user_machine_code->machine_user->machine->nick_name)){
                            $data[] = $user_machine_code->machine_user->machine->nick_name;
                        } elseif(isset($user_machine_code->machine->nick_name)) {
                            $data[] = $user_machine_code->machine->nick_name;
                        }
                        $data[] = (isset($user_machine_code->created_user->full_name)) ? $user_machine_code->created_user->full_name : '';
                        $data[] = $user_machine_code->serial_number;
                        $data[] = $user_machine_code->uses;
                        $data[] = (!empty($user_machine_code->used_date)) ? Carbon::createFromFormat('Y-m-d', $user_machine_code->used_date)->format('m/d/Y') : '';
                        $data[] = $user_machine_code->code;
                        $data[] = $user_machine_code->created_at->format('m/d/Y h:i:s A');
                        fputcsv($fp, $data);
                    }
                }
            }


            else if($type == 'machine-serial') {

                fputcsv($fp, [$customer->name.' Export Codes at '.date('m/d/Y h:i:s A')]);
                fputcsv($fp, ['']);
                fputcsv($fp, ['Machine Name', 'Serial', 'Status']);

                $customer_machines = $customer->machine()->active()->get();
                if(count($customer_machines) > 0){

                    foreach ($customer_machines as $customer_machine) {
                        if(isset($customer_machine->machine->nick_name)){
                            $user_generated_codes = $customer_machine->code()->orderBy('created_at', 'desc')->pluck('serial_number', 'id')->toArray();
                            $user_generated_codes = (array_unique($user_generated_codes));
                            if(count($user_generated_codes) > 0){

                                foreach($user_generated_codes AS $id => $serial_number){

                                    $checkSerial =  $customer_machine->code()->where('id', $id)->first();
                                    if($checkSerial){
                                        $data = [
                                            $customer_machine->machine->nick_name,
                                            $serial_number,
                                            ($checkSerial->block_serial_number == 1) ? 'Blocked' : 'Allowed'
                                        ];
                                        fputcsv($fp, $data);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            else if($type == 'machine-type') {

                $customer_machines = $customer->machine()->active()->get();

                fputcsv($fp, [$customer->name.' Export Machine Type at '.date('m/d/Y h:i:s A')]);
                fputcsv($fp, ['']);
                fputcsv($fp, ['Machine Name', 'Credit Pool', 'Status']);

                if(count($customer_machines) > 0) {
                    foreach ($customer_machines AS $customer_machine) {
                        $data = [
                            (isset($customer_machine->machine->nick_name)) ? $customer_machine->machine->nick_name : '',
                            $customer_machine->credits,
                            ($customer_machine->allow_generate_code  == 1) ? 'Allowed' : 'Blocked'
                        ];
                        fputcsv($fp, $data);
                    }
                }
            }
        }
    }
}
