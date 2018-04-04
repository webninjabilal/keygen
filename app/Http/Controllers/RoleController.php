<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user-role.index');
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
    public function store(RoleRequest $request)
    {
        $role = new Role();
        $creds = $request->only('name', 'display_name', 'description');
        $role = Role::create($creds);
        //$role->save($creds);
        flash()->success('User Role saved successfully!');
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
        $user_role = Role::findOrFail($id);
        return view('user-role._form', compact('user_role'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        $user_role_id = $request->input('user_role_id');
        $role = Role::findOrFail($user_role_id);
        $creds = $request->only('name', 'display', 'description');

        $role->update($creds);

        flash()->success('User Role updated successfully');
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
        $role = Role::findOrFail($id);
        $role->delete();
        flash()->success('User Role Deleted Successfully');
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
        $orderby    = $order[0]['dir'];

        $query = Role::where('id', '>', '0')->whereNotIn('name', ['account-owner', 'administrator']);
        if ($search != '') {
            $query->where(function ($inner) use ($search){
                $inner->orWhere('name', 'like', '%' . $search . '%');
                $inner->orWhere('display_name', 'like', '%' . $search . '%');
                $inner->orWhere('description', 'like', '%' . $search . '%');
            });
        }


        $query->orderBy('id', 'DESC');
        $total_records = $query->get()->count();

        $roles = $query->limit($limit)->offset($start)->get();
        $records = [];

        if($roles) {
            foreach($roles AS $role) {
                $data = [
                    $role->name,
                    $role->display_name,
                    '<a href="javascript:void(0)" class="btn btn-white btn-sm" onclick="getUserRole(' . $role->id . ')"><i class="fa fa-pencil"></i> Edit</a>
                     <a href="javascript:void(0)" class="btn btn-white btn-sm" onclick="deleteUserRole(' . $role->id . ')"><i class="fa fa-trash-o"></i> Delete</a>
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
