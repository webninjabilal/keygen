<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\MachineRequest;
use App\Machine;
use App\Sheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MachineController extends Controller
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
        $sheets = $this->company->sheet()->orderBy('id', 'desc')->pluck('title', 'id')->toArray();
        return view('machine.index', compact('sheets'));
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
    public function store(MachineRequest $request)
    {
        $machine = new Machine();
        $input = $request->except('_token');

        $input['company_id'] = $this->company->id;
        /*if(!$machine->checkSerialAvail($input['serial_number'], $input['sheet_id'])){
            return json_encode(['success' => false, 'errors' => 'Serial Number is not available please try it with different one.']);
        }*/
        $this->user->machine()->create($input);
        flash()->success('Machine has been added successfully!');
        return json_encode(['success' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $machine = Machine::findOrFail($id);
        $sheets = $this->company->sheet()->orderBy('id', 'desc')->pluck('title', 'id')->toArray();
        return view('machine._form', compact('machine', 'sheets'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MachineRequest $request, $id)
    {
        $machine_id  = $request->input('machine_id');
        $machine = Machine::findOrFail($machine_id);
        $input = $request->except('_token', 'machine_id');
        /*if(!$machine->checkSerialAvail($input['serial_number'], $input['sheet_id'])){
            return json_encode(['success' => false, 'errors' => 'Serial Number is not available please try it with different one.']);
        }*/
        //$input['is_time_base'] = (isset($input['is_time_base'])) ? 1 : 0;
        $machine->update($input);
        flash()->success('Machine has been updated successfully!');
        return json_encode(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $machine_id = $request->input('machine_id');
        $sheet = Machine::findOrFail($machine_id);
        $sheet->delete();
        flash()->success('Machine Deleted Successfully');
        return json_encode(['success' => true]);
    }

    public function records(Request $request)
    {

        $search = $request->input('search');
        $search = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');

        $filter = $request->input('columns');

        $query = $this->company->machine()->where('id', '>', 0);
        if ($search != '') {
            $query->where(function ($inner) use ($search){
                $inner->orWhere('nick_name', 'like', '%' . $search . '%');
                $inner->orWhere('prefix', 'like', '%' . $search . '%');
                $inner->orWhere('serial_number', 'like', '%' . $search . '%');
            });
        }


        $query->orderBy('id', 'DESC');
        $total_records = $query->count();

        $machines = $query->limit($limit)->offset($start)->get();
        $records = [];

        if($machines) {
            foreach($machines AS $machine) {

//                ($machine->is_time_base == 1) ? 'Time Based' : 'Unit Based',
//                    (isset($machine->sheet->title)) ? $machine->sheet->title : '',

                $data = [
                    $machine->nick_name,
                    $machine->prefix,
                    Machine::statuses($machine->status),
                    '<a href="javascript:void(0)" class="btn btn-white btn-sm" onclick="getMachine(' . $machine->id . ')"><i class="fa fa-pencil"></i> Edit</a>
                     <a href="javascript:void(0)" class="btn btn-white btn-sm" onclick="deleteMachine(' . $machine->id . ')"><i class="fa fa-trash-o"></i> Delete</a>
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
