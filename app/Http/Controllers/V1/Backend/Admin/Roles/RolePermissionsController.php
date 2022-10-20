<?php

namespace App\Http\Controllers\V1\Backend\Admin\Roles;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Services\AdminServices\RolePermissionServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


class RolePermissionsController extends Controller
{
    public function rolePermissions(Role $role)
    {
        $response = new RolePermissionServices();
        return $response->rolePermissions($role);

    }

    public function rolePermissionsStore(Request $request,Role $role)
    {

        $response = new RolePermissionServices();
        return $response->rolePermissionsStore($request,$role);
    }
}
