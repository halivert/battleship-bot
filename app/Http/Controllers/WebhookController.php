<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
	public function get()
	{
		return view('set_webhook');
	}

	public function update(Request $request)
	{
		$request->all();
	}
}
