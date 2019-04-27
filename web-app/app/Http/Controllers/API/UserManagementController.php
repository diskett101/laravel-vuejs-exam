<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Redis;
use Response;
use Validator;

use App\User;
use App\Http\Resources\UserResource;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('info')
                 // ->latest()
                 ->excludeAdmins()
                 ->excludeDeleted()
                 ->get();
                 // ->paginate(20);
        return UserResource::collection($users);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post_rules = [
            'username' => 'required|min:8|max:50|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:50',
            'confirm_password' => 'required|min:8|max:50',
            'firstname' => 'required|min:3|max:50',
            'lastname' => 'required|min:3|max:50',
            'address' => 'required|min:10|max:255',
            'postcode' => 'required|min:4|integer',
            'phone_number' => 'required|min:7',
        ];
        $request_validation = Validator::make($request->all(), $post_rules);
        if ($request_validation->fails()) {
            return Response::json([
                'error' => $request_validation->errors()
            ], 400);
        }
        $request_data = $request->all();
        if ($request_data['password'] != $request_data['confirm_password']) {
            return Response::json([
                'error' => [
                    'password' => [
                        'Passwords do not match'
                    ]
                ]
            ], 400);
        }

        $user = new User();
        $user->username = $request_data['username'];
        $user->email = $request_data['email'];
        $user->password = $request_data['password'];
        $user->user_type = User::$user_types['default']['code'];
        $user->save();

        $user->info()->create([
            'user_id' => $user->id,
            'firstname' => $request_data['firstname'],
            'lastname' => $request_data['lastname'],
            'address' => $request_data['address'],
            'postcode' => $request_data['postcode'],
            'phone_number' => $request_data['phone_number'],
        ]);
        return Response::json(new UserResource($user), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $update_rules = [
            'username' => ('min:8|max:50|unique:users,id,' . $id),
            'email' => ('email|unique:users,id,' . $id),
            'password' => 'min:8|max:50',
            'confirm_password' => 'min:8|max:50',
            'firstname' => 'min:3|max:50',
            'lastname' => 'min:3|max:50',
            'address' => 'min:10|max:255',
            'postcode' => 'min:4|integer',
            'phone_number' => 'min:7',
        ];
        $request_validation = Validator::make($request->all(), $update_rules);
        if ($request_validation->fails()) {
            return Response::json([
                'error' => $request_validation->errors()
            ], 400);
        }

        $request_data = $request->all();

        if (isset($request_data['password'])) {
            $password_confirm = isset($request_data['confirm_password']) ? $request_data['confirm_password'] : '';
            if ($request_data['password'] != $password_confirm) {
                return Response::json([
                    'error' => [
                        'password' => [
                            'Passwords do not match'
                        ]
                    ]
                ], 400);
            }
        }

        $user = User::find($id);
        if (isset($request_data['username'])) {
            $user->username = $request_data['username'];
        }
        if (isset($request_data['email'])) {
            $user->email = $request_data['email'];
        }
        if (isset($request_data['password'])) {
            $user->password = $request_data['password'];
        }

        $info_data = [];
        if (isset($request_data['firstname'])) {
            $info_data['firstname'] = $request_data['firstname'];
        }
        if (isset($request_data['lastname'])) {
            $info_data['lastname'] = $request_data['lastname'];
        }
        if (isset($request_data['address'])) {
            $info_data['address'] = $request_data['address'];
        }
        if (isset($request_data['postcode'])) {
            $info_data['postcode'] = $request_data['postcode'];
        }
        if (isset($request_data['phone_number'])) {
            $info_data['phone_number'] = $request_data['phone_number'];
        }
        if (count($info_data) > 0) {
            $user->info->update($info_data);
        }
        $user->save();
        return Response::json(new UserResource($user), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $token_header = $request->header('token');
        $requesting_user_id = Redis::get($token_header);
        if ($id != $requesting_user_id) {
            // IF not requesting user's id
            if (!empty($user)) {
                $user->removeInfo();
                $user->delete();
            }
        }
        return Response::json([], 204);
    }

   public function remove_selected(Request $request)
   {
        if ($request->has('ids')) {
            $token_header = $request->header('token');
            $requesting_user_id = Redis::get($token_header);
            $ids_qs = $request->input('ids');
            $ids = explode(',', $ids_qs);
            foreach ($ids as $key => $user_id) {
                if ($user_id == $requesting_user_id) {
                    // Skip requesting user's id
                    continue;
                }
                $user = User::find($user_id);
                if (empty($user)) {
                    continue;
                }
                $user->removeInfo();
                $user->delete();
            }
        }
        return Response::json([], 204);
   }
}
