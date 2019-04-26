<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $users = User::with('info')->latest()
                 ->excludeAdmins()
                 ->excludeDeleted()
                 ->paginate(20);
        return UserResource::collection($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'username' => 'required|min:4|max:50|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:50',
            'firstname' => 'required|min:3|max:50',
            'lastname' => 'required|min:3|max:50',
            'address' => 'required|min:5|max:255',
            'postcode' => 'required|min:4|max:5',
            'phone_number' => 'required|min:7|max:10',
        ];
        $request_validation = Validator::make($request->all(), $post_rules);
        if ($request_validation->fails()) {
            return Response::json([
                'error' => $request_validation->errors()
            ], 400);
        }
        $request_data = $request->all();
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
        return new UserResource($user);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            'username' => ('required|min:8|max:50|unique:users,id,' . $id),
            'email' => ('required|email|unique:users,id,' . $id),
            'password' => 'required|min:8|max:50',
            'firstname' => 'required|min:3|max:50',
            'lastname' => 'required|min:3|max:50',
            'address' => 'required|min:5|max:255',
            'postcode' => 'required|min:4|max:5',
            'phone_number' => 'required|min:7|max:10',
        ];
        $request_validation = Validator::make($request->all(), $update_rules);
        if ($request_validation->fails()) {
            return Response::json([
                'error' => $request_validation->errors()
            ], 400);
        }
        $request_data = $request->all();
        $user = User::find($id);
        $user->username = $request_data['username'];
        $user->email = $request_data['email'];
        $user->password = $request_data['password'];
        $user->info->update([
            'firstname' => $request_data['firstname'],
            'lastname' => $request_data['lastname'],
            'address' => $request_data['address'],
            'postcode' => $request_data['postcode'],
            'phone_number' => $request_data['phone_number'],
        ]);
        $user->save();
        return new UserResource($user);
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
        $user->removeInfo();
        $user->delete();
   }

   public function removeAll(Request $request)
   {
        return $request;
   }
}
