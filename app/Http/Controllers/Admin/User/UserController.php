<?php

namespace App\Http\Controllers\Admin\User;

use App\Handlers\UserHandler;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
     * Function to show all users.
     */
    public function index()
    {
        // Define all the users:
        $users = User::all();
        // returns view template and $user variable to use in the view.
        return view('admin.user.index', compact('users'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();

        return view('admin.user.edit', compact('user', 'roles'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validates the requested fields
        $this->validate($request, [
            'username' => 'required',
            'email' => 'required|email',
            'role_id' => 'required'
        ]);
        // updates user with all requests.
        User::find($id)->update($request->all());
        //redirects to route with success message.
        return redirect()->route('adminUsers')
            ->with('success', 'Item updated successfully');
    }

    /**
     * Function to ban specific user.
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     */
    public function ban(Request $request)
    {
        // gets all requests.
        $input = $request->all();
        // checks if id exist in request.
        if (!empty($input['id'])) {
            // gets single user by id.
            $user = User::find($input['id']);
            // user gets banned.
            $user->bans()->create([
                'expired_at' => '+1 week',
                'comment' => $request->baninfo
            ]);
        }
        // redirect to route with success message.
        return redirect()->route('adminUsers')->with('success', $user->username . ' banned successfully..');
    }

    /**
     * Function to revoke banned user.
     * Show the form for creating a new resource.
     *
     * @param $id
     * @return Response
     */
    public function revoke($id)
    {
        // checks if id is in url.
        if (!empty($id)) {
            // get specific user.
            $user = User::find($id);
            // unban user.
            $user->unban();
        }
        // redirect to route with success message and name of user.
        return redirect()->route('adminUsers')
            ->with('success', $user->username . ' successfully revoked!');
    }
}
