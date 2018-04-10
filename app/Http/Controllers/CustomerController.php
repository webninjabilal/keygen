<?php

namespace App\Http\Controllers;

use App\Company;
use App\Customer;
use App\Http\Requests\CustomerRequest;
use App\MachineUserCode;
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
                $inner->orWhereIn('machine_user_id', $customer->machine()->acrive()->pluck('id')->toArray());
                $inner->orWhereIn('created_by', $customer->user()->pluck('id')->toArray());
            })->orderBy('created_at', 'desc')->paginate(25);
            return view('customer.detail', compact('customer', 'user_machine_codes', 'is_customer', 'is_user'));
        }
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

        $query = $this->company->customer()->where('name', '!=', '');
        if ($search != '') {
            $query->where(function ($inner) use ($search){
                $inner->orWhere('name', 'like', '%' . $search . '%');
            });
        }


        $query->orderBy('id', 'DESC');
        $total_records = $query->count();

        $customers = $query->limit($limit)->offset($start)->get();
        $records = [];

        if($customers) {
            foreach($customers AS $customer) {
                $data = [
                    '<a href="'.route('customer_detail', [$customer->id]).'">'.$customer->name.'</a>',
                    '<a href="javascript:void(0)" class="btn btn-white btn-sm" onclick="getCustomer(' . $customer->id . ')"><i class="fa fa-pencil"></i> Edit</a>
                     <a href="javascript:void(0)" class="btn btn-white btn-sm" onclick="deleteCustomer(' . $customer->id . ')"><i class="fa fa-trash-o"></i> Delete</a>
                    ',
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
}
