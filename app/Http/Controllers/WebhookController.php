<?php

namespace App\Http\Controllers;

use App\Models\Update;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
	public function post(Request $request, $bot)
	{
		$attrs = $this->validate($request, Update::getRules());
		if (!Update::find($attrs['update_id'])) {
			$update = new Update($attrs);

			if ($update and $update->type !== 'unknown') {
				$update->save();
				try {
					DB::transaction(function () use ($update, $bot) {
						$result = $bot->handleUpdate($update);
						if ($result->has_error) {
							throw new Exception($result->error->description);
						} else {
							$update->was_processed = 1;
							$update->save();
						}
					}, 3);
				} catch (Exception $e) {
					Log::error($e->getMessage());
				}
			}
		}

		Log::info("{$request->ip()} - payload\t$request");
	}
}
