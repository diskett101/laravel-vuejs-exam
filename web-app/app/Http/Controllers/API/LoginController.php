<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Redis;
use Response;
use Validator;

use App\Token;
use App\User;

class LoginController extends Controller
{
	public function login(Request $request)
	{
		$rules = [
			'email' => 'required',
			'password' => 'required',
		];
		$request_validation = Validator::make($request->all(), $rules);
		if ($request_validation->fails()) {
			return Response::json([
				'error' => $request_validation->errors()
			], 400);
		}
		$email = $request->get('email');
		$password = $request->get('password');
		if (Auth::attempt(['email' => $email, 'password' => $password])) {
			$user = $request->user();
			if ($user->user_type != User::$user_types['admin']['code']) {
				return Response::json([
					'error' => "Unauthorized, user type denied."
				], 401);
			}
			$token = "token_" . base64_encode(uniqid());
			Token::create([
				'user_id' => $user->id,
				'token' => $token
			]);
			Redis::setex($token, 604800, $user->id);
			return Response::json([
				'token' => $token
			], 200);
		} else {
			return Response::json([
				'error' => "Invalid Credentials"
			], 400);
		}
	}
	public function logout(Request $request)
	{
		$token_header = $request->header('token');
		Redis::del($token_header);
		return Response::json([], 204);
	}
}
