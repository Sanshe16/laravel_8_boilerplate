<?php

namespace App\Http\Controllers\V1\Base;

use App\Http\Controllers\Controller;

use App\Services\Backend\BackendService;
use Illuminate\Validation\UnauthorizedException;

class V1BaseController extends Controller
{
	protected $api;

	public function __construct(BackendService $backend_service ) {
		$this->api = $backend_service;
		$this->middleware(function ($request, $next) {
			if(session()->has('accessToken')){
				$this->api->setToken(session('accessToken'));
			}
			return $next($request);
		});
	}

	protected function permitted($route){
		if(session()->has('user.routePermissions')){
			if(in_array($route, array_keys(session()->get('user.routePermissions')))){
				return;
			}
		}
		throw new UnauthorizedException('You are not authorized, please check your permissions');
	}

	protected function isPermitted($route){
		if(session()->has('user.routePermissions')){
			if(in_array($route, array_keys(session()->get('user.routePermissions')))){
				return true;
			}
		}
		return false;
	}

}
