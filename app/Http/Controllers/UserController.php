<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pages.auth.login');
    }

    public function authenticate(Request $request)
    {
       $validation = Validator::make($request->all(),[
           'email' => 'required|email',
           'password' => 'required|min:8'
        ]);

        if($validation->passes()){
            if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password],$request->get('remember'))){
               return redirect()->route('admin.dashboard');
            }

            else{
              return redirect()->route('admin.login')->with('error','Invalid Credentials');
            }

        }
        else{
            return redirect()->route('admin.login')->withErrors($validation)->withInput($request->only('email'));
        }
    }


    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add User';
        return view('admin.pages.user.create',compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate the incoming request data
    $validation = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    // Check if validation fails
    if ($validation->fails()) {
        return response()->json(['status'=>false, 'errors'=>$validation->errors()]);
        // return redirect()->back()->withErrors($validator)->withInput();
    }
    else{

    // Create a new user instance
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = bcrypt($request->password); // Hash the password
    $user->status = $request->status;
    $user->save(); // Save the user to the database

    session()->flash('success', 'User created Successfully');

    // Return a success response
    return response()->json(['status'=>true, 'success'=>'User created Successfully', 'data'=> $user]);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $users = User::latest();
        if(!empty($request->get('keyword'))){
            $users= $users->where('name','like','%'.$request->get('keyword').'%');
        }

        $users = $users->latest()->paginate(8);
        $title = 'User List';
        $data = [$users, $title];
        return view('admin.pages.user.manage',compact('users','title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user=User::find($id);
        $title = 'Edit User';
        return view('admin.pages.user.edit',compact('user','title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(empty($id)){
            session()->flash('error', 'User Not Found');
            return response()->json(['status'=>false, 'notFound'=>'true','error'=>'User not found']);
        }
        // Validate the incoming request data
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:25',

        ]);

        // Check if validation fails
        if ($validation->fails()) {
            return response()->json(['status'=>false, 'errors'=>$validation->errors()]);
            // return redirect()->back()->withErrors($validator)->withInput();
        }
        else{

        // Create a new user instance
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password); // Hash the password
        $user->status = $request->status;
        $user->update(); // Save the user to the database

        session()->flash('success', 'Updated Successfully');

        // Return a success response
        return response()->json(['status'=>true, 'success'=>'User updated Successfully', 'data'=> $user]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(empty($id)){
            session()->flash('error', 'User Not Found');
            return response()->json(['status'=>false , 'error','User not found']);
        }

        $user = User::find($id);
        $user->delete();
        session()->flash('success', 'User deleted Successfully');
        return response()->json(['status'=>true , 'success'=>'category deleted successfully']);
    }
}
