<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\SheetRequest;
use App\Sheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SheetController extends Controller
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
        return view('sheet.index');
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
    public function store(SheetRequest $request)
    {
        $input = $request->except('_token');
        $input['company_id'] = $this->company->id;
        $this->user->sheet()->create($input);
        flash()->success('Sheet Has been added successfully!');
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
        $sheet = $this->company->sheet()->where('id', $id)->first();
        if($sheet) {
            return view('sheet._form', compact('sheet'));
        } else {
            return 'Ooops, something went wrong';
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SheetRequest $request, $id)
    {
        $sheet_id  = $request->input('sheet_id');
        $sheet = Sheet::findOrFail($sheet_id);
        $sheet->update($request->except('_token', 'sheet_id'));
        flash()->success('Sheet has been updated successfully!');
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
        $sheet_id = $request->input('sheet_id');
        $sheet = Sheet::findOrFail($sheet_id);
        $sheet->delete();
        flash()->success('Sheet Deleted Successfully');
        return json_encode(['success' => true]);
    }

    public function records(Request $request)
    {

        $search = $request->input('search');
        $search = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');

        $filter = $request->input('columns');

        $query = $this->company->sheet()->where('title', '!=', '');
        if ($search != '') {
            $query->where(function ($inner) use ($search){
                $inner->orWhere('title', 'like', '%' . $search . '%');
                $inner->orWhere('prefix', 'like', '%' . $search . '%');
                $inner->orWhere('minimum', 'like', '%' . $search . '%');
                $inner->orWhere('maximum', 'like', '%' . $search . '%');
            });
        }


        $query->orderBy('id', 'DESC');
        $total_records = $query->count();

        $sheets = $query->limit($limit)->offset($start)->get();
        $records = [];

        if($sheets) {
            foreach($sheets AS $sheet) {
                $data = [
                    $sheet->title,
                    $sheet->prefix,
                    $sheet->minimum,
                    $sheet->maximum,
                    '<a href="javascript:void(0)" class="btn btn-white btn-sm" onclick="getSheet(' . $sheet->id . ')"><i class="fa fa-pencil"></i> Edit</a>
                     <a href="javascript:void(0)" class="btn btn-white btn-sm" onclick="deleteSheet(' . $sheet->id . ')"><i class="fa fa-trash-o"></i> Delete</a>
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
