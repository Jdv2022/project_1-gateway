<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Log;
use Carbon\Carbon;

class FEUtilityController extends __ApiBaseController {
    
	public function dayToday(Request $request): JsonResponse {
		$validated = $request->validate([
			'timezone' => 'required'
		]);
		$timezone = $validated['timezone'];
		$now = Carbon::now($timezone);

		$weekday = $now->format('D');
		$month = $now->format('M');
		$day = $now->format('d');
		$year = $now->format('Y');
		$time = $now->format('H:i:s');
		$offset = $now->format('O');
		$tzName = timezone_name_get($now->getTimezone());

		$formatted = sprintf(
			'%s %s %s %s %s GMT%s (%s)',
			$weekday,
			$month,
			$day,
			$year,
			$time,
			$offset,
			$tzName
		);


		$today = [
			'date' => $formatted,      
        	'time' => $now->toTimeString(),     
		];

		return $this->returnSuccess(data: $today);
	}

}
