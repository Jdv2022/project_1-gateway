<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends __ApiBaseController {

	public function returnSuccessTest($data, $message) {
		return $this->returnSuccess($data, $message);

	}

	public function returnFailTest($data, $message) {
		return $this->returnFail($data, $message);
	}

}
