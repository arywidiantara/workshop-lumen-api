<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
            'name'         => 'required',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:6',
            'image_name'   => 'required',
            'image_base64' => 'required',
        ];

        // validation
        $this->validate($request, $validation);

        $image_name = $request->input('image_name');
        $image      = $request->input('image_base64');

        // get ekstension
        $image_name = explode(".", $image_name);
        $extension  = end($image_name);

        // decode base64
        $image_decode = base64_decode($image);

        // set name file
        $filename       = str_random(25) . "." . $extension;
        $directory_name = 'users/' . $filename;

        // save image
        Storage::put($directory_name, $image_decode, 'public');

        $data = [
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'image'    => $filename,
        ];

        $user = User::create($data);

        return $user;
    }

    public function update(Request $request, $id = '')
    {
        $validation = [
            'name'         => 'required',
            'email'        => 'required|email|unique:users,email,' . $id,
            'password'     => 'min:6',
            'image_name'   => '',
            'image_base64' => '',
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

        if (!empty($request->input('password')))
        {
            $user->password = Hash::make($request->input('password'));
        }

        // check image
        if (!empty($request->input('image_name')) && !empty($request->input('image_base64')))
        {
            $image_name = $request->input('image_name');
            $image      = $request->input('image_base64');

            // get ekstension
            $image_name = explode(".", $image_name);
            $extension  = end($image_name);

            // decode base64
            $image_decode = base64_decode($image);

            // set name file
            $filename       = str_random(25) . "." . $extension;
            $directory_name = 'users/' . $filename;

            // save image
            Storage::put($directory_name, $image_decode, 'public');

            $user->image = $filename;
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
