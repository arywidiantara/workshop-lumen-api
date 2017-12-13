<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // return response()->json(['Workshop Lumens!']);
        return User::all();
    }

    public function store(Request $request, $id = '')
    {
        $validation = [
            'name' => 'required',
        ];

        if (empty($id) || $id == '')
        {
            $validation['email']    = 'required|email|unique:users,email';
            $validation['password'] = 'required|min:6';
        }
        else
        {
            $validation['email']    = 'required|email|unique:users,email,' . $id;
            $validation['password'] = 'min:6';
        }

        // validation
        $this->validate($request, $validation);

        $data = [
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ];

        if (empty($id) || $id == '')
        {
            $user = User::create($data);
        }
        else
        {
            $user        = User::firstOrCreate(['id' => $id]);
            $user->name  = $data['name'];
            $user->email = $data['email'];

            if (!empty($data['password']))
            {
                $user->password = $data['password'];
            }

            $user->save();
        }

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
