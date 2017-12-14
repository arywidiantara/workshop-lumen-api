<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::OrderBy('name', 'desc');

        if ($request->input('name'))
        {
            $users = $users->where('name', 'Like', '%' . $request->input('name') . '%');
        }

        $users->simplePaginate(1);
        $users = $users->get();

        return response()->json(['users' => $users]);
    }

    public function store(Request $request)
    {
        $validation = [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ];

        // validation
        $this->validate($request, $validation);

        $data = [
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ];

        return User::create($data);
    }

    public function update(Request $request, $id = '')
    {
        $validation = [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'min:6',
        ];

        // validation
        $this->validate($request, $validation);

        $user = User::find($id);

        if (empty($user))
        {
            return response()->json(['user not found']);
        }

        $user->name  = $request->input('name');
        $user->email = $request->input('email');

        if (!empty($data['password']))
        {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return $user;
    }

    public function delete(Request $request, $id = '')
    {
        $user = User::find($id);

        if (empty($user))
        {
            return response()->json(['user not found']);
        }

        $user->delete();

        return response()->json(["success delete"]);
    }
}
