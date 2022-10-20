<?php

namespace App\Http\Controllers\V1\Backend\Admin\Roles;

use App\Http\Requests\Roles\StoreRolesRequest;
use App\Http\Requests\Roles\UpdateRolesRequest;
use App\Models\User;
use App\Services\AdminServices\RoleService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $list = new RoleService();
        if ($request->ajax()) {
            return $list->roleList();
        }

        return view('backend.content.admin.user_management.roles.index');
    }

    public function rolesAttachedUsers(Request $request,Role $role)
    {
        $attach = new RoleService();
        return $attach->rolesAttachedUsers($role);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.content.admin.user_management.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRolesRequest $request)
    {
        $create = new RoleService();
        $create->roleStore($request);
        return  redirect()->route('roles.index')->with('success','Role Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $show = new RoleService();
        $role =$show->findRole($role->id);
        if(request()->ajax()==true) {
           return $show->viewRoles($role);
        }
        return view('backend.content.admin.user_management.roles.show',['role'=>$role]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return view('backend.content.admin.user_management.roles.edit',['role'=>$role]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRolesRequest $request, $id)
    {
        $update = new RoleService();
        $update->roleUpdate($request,$id);
        return  redirect()->route('roles.index')->with('success','Role Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function attachRoleToUserCreate()
    {
        $response = new RoleService();
        return $response->attachRoleToUserCreate();

    }
    public function attachRoleToUserStore(Request $request,User $user)
    {
        $response = new RoleService();
        return $response->attachRoleToUserStore($request,$user);
    }

}
