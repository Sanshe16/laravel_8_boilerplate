<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;

class ApiV1Controller extends Controller
{

	// override any ApiController methods if version specific required
	protected  function toBag($request, $bag){
		return new $bag(
			$request
		);
	}

	/**
	 * Handles the request for extra parameters
	 */
	protected function defaultRequestFilter(){
		// need to apply a common function for this
		if(!isset(request()->start)) request()->request->add(['start' => 0]);

		if(!isset(request()->length)) request()->request->add(['length' => 25]);


	}

	/**
	 * Handle the request for wrapping transformation
	 * @param $bag
	 *
	 * @return array|\Illuminate\Http\Request|string
	 */
	protected function handleRequest( $bag ) {
		$configRequest = new $bag(request()->toArray());
		request()->merge($configRequest->attributes());
		return request();
	}
}
