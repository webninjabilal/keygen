<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\CompanyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class CompanyController extends Controller
{
    
    protected $user = null;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
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
        return view('company.index');
    }

    public function getChangeCompany($company_id)
    {
        $companies = Company::userLevelCompanies($this->user->id);
        if(isset($companies[$company_id])) {
            Session::put('company_main', $company_id);
            Session::save();
            return json_encode(['success' => true]);
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
    public function store(CompanyRequest $request)
    {
        $input = $request->except('_token');
        Company::create($input);
        flash()->success('Company has been added successfully!');
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
        $company = Company::findOrFail($id);
        return view('company._form', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyRequest $request, $id)
    {
        $company_id  = $request->input('company_id');
        $company = Company::findOrFail($company_id);
        $company->update($request->except('_token', 'company_id'));
        flash()->success('Company has been updated successfully!');
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
        $company_id = $request->input('company_id');
        $company = Company::findOrFail($company_id);
        $company->delete();
        flash()->success('Company Deleted Successfully');
        return json_encode(['success' => true]);
    }

    public function records(Request $request)
    {

        $search = $request->input('search');
        $search = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');

        $filter = $request->input('columns');

        $query = Company::where('name', '!=', '');
        if ($search != '') {
            $query->where(function ($inner) use ($search){
                $inner->orWhere('name', 'like', '%' . $search . '%');
                $inner->orWhere('address', 'like', '%' . $search . '%');
            });
        }


        $query->orderBy('id', 'DESC');
        $total_records = $query->count();

        $companies = $query->limit($limit)->offset($start)->get();
        $records = [];

        if($companies) {
            foreach($companies AS $company) {
                $data = [
                    $company->name,
                    '<a href="javascript:void(0)" class="btn btn-white btn-sm" onclick="getCompany(' . $company->id . ')"><i class="fa fa-pencil"></i> Edit</a>
                     <a href="javascript:void(0)" class="btn btn-white btn-sm" onclick="deleteCompany(' . $company->id . ')"><i class="fa fa-trash-o"></i> Delete</a>
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
