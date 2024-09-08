<?php

namespace App\Http\Controllers;

use App\Models\category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add category';
        return view('admin.pages.category.create',compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate the incoming request data
    $validation = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'slug' => 'required|unique:categorys',

    ]);

    // Check if validation fails
    if ($validation->fails()) {
        return response()->json(['status'=>false, 'errors'=>$validation->errors()]);
    }
    else{

    // Create a new category instance
    $category = new category();
    $category->name = $request->name;
    $category->slug = $request->slug;
    $category->showhome = $request->showhome;
    $category->status = $request->status;
    $category->save(); // Save the category to the database

    session()->flash('success', 'category created Successfully');

    // Return a success response
    return response()->json(['status'=>true, 'success'=>'category created Successfully', 'data'=> $category]);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $categorys = category::latest();
        if(!empty($request->get('keyword'))){
            $categorys= $categorys->where('name','like','%'.$request->get('keyword').'%');
        }

        $categorys = $categorys->paginate(8);
        $title = 'category List';
        $data = [$categorys, $title];
        return view('admin.pages.category.manage',compact('categorys','title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category=category::find($id);
        $title = 'Edit category';
        return view('admin.pages.category.edit',compact('category','title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(empty($id)){
            session()->flash('error', 'category Not Found');
            return response()->json(['status'=>false, 'notFound'=>'true','error'=>'category not found']);
        }
        // Validate the incoming request data
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required',

        ]);

        // Check if validation fails
        if ($validation->fails()) {
            return response()->json(['status'=>false, 'errors'=>$validation->errors()]);
            // return redirect()->back()->withErrors($validator)->withInput();
        }
        else{

        // Create a new category instance
        $category = category::find($id);
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->showhome = $request->showhome;
        $category->update(); // Save the category to the database
        session()->flash('success', 'Updated Successfully');

        // Return a success response
        return response()->json(['status'=>true, 'success'=>'category updated Successfully', 'data'=> $category]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(empty($id)){
            session()->flash('error', 'category Not Found');
            return response()->json(['status'=>false , 'error','category not found']);
        }

        $category = category::find($id);
        $category->delete();
        session()->flash('success', 'category deleted Successfully');
        return response()->json(['status'=>true , 'success'=>'category deleted successfully']);
    }
}
