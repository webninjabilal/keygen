<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\UnitRequest;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
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
        return view('unit.index');
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
    public function store(UnitRequest $request)
    {
        $input = $request->except('_token');
        $input['company_id'] = $this->company->id;
        $this->user->unit()->create($input);
        flash()->success('Unit has been added successfully!');
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
        $unit = Unit::findOrFail($id);
        return view('unit._form', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UnitRequest $request, $id)
    {
        $unit_id  = $request->input('unit_id');
        $unit = Unit::findOrFail($unit_id);
        $unit->update($request->except('_token', 'unit_id'));
        flash()->success('Unit has been updated successfully!');
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
        $unit_id = $request->input('unit_id');
        $unit = $this->company->unit()->where('id', $unit_id)->first();
        if($unit) {
            $unit->delete();
            flash()->success('Unit Deleted Successfully');
        }
        flash()->error('Unit has not found');
        return json_encode(['success' => true]);
    }

    public function records(Request $request)
    {

        $search = $request->input('search');
        $search = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');

        $filter = $request->input('columns');

        $query = $this->company->unit()->where('name', '!=', '');
        if ($search != '') {
            $query->where(function ($inner) use ($search){
                $inner->orWhere('name', 'like', '%' . $search . '%');
                $inner->orWhere('sku', 'like', '%' . $search . '%');
            });
        }


        $query->orderBy('id', 'DESC');
        $total_records = $query->count();

        $units = $query->limit($limit)->offset($start)->get();
        $records = [];

        if($units) {
            foreach($units AS $unit) {
                $data = [
                    $unit->name,
                    $unit->sku,
                    '<a href="javascript:void(0)" class="btn btn-white btn-sm" onclick="getUnit(' . $unit->id . ')"><i class="fa fa-pencil"></i> Edit</a>
                     <a href="javascript:void(0)" class="btn btn-white btn-sm" onclick="deleteUnit(' . $unit->id . ')"><i class="fa fa-trash-o"></i> Delete</a>
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
