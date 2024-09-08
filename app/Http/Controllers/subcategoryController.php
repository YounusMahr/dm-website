<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class subcategoryController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorys = category::latest()->get();
        $title = 'Add subcategory';
        return view('admin.pages.subcategory.create',compact('title','categorys'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate the incoming request data
    $validation = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'slug' => 'required|unique:subcategorys',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048'

    ]);

    // Check if validation fails
    if ($validation->fails()) {
        return response()->json(['status'=>false, 'errors'=>$validation->errors()]);
    }
    else{

    // Create a new subcategory instance
    $subcategory = new subcategory();
    $subcategory->name = $request->name;
    $subcategory->slug = $request->slug;

    if($request->hasfile('image')){
        $file =$request->file('image');
        $filename = time().'.'.$file->getClientOriginalExtension();
        $file->move('uploads/subcategory/',$filename);
        $subcategory->image =$filename;
    }

    $subcategory->categorys_id = $request->category;

    $subcategory->showhome = $request->showhome;
    $subcategory->status = $request->status;
    $subcategory->save(); // Save the subcategory to the database

    session()->flash('success', 'subcategory created Successfully');

    // Return a success response
    return response()->json(['status'=>true, 'success'=>'subcategory created Successfully', 'data'=> $subcategory]);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $subcategorys = subcategory::select('subcategorys.*','categorys.name as categoryName')->latest('subcategorys.id')
        ->leftjoin('categorys','categorys.id','subcategorys.categorys_id');
        if(!empty($request->get('keyword'))){
            $subcategorys= $subcategorys->where('name','like','%'.$request->get('keyword').'%');
        }

        $subcategorys = $subcategorys->paginate(8);
        $title = 'subcategory List';
        $data = [$subcategorys, $title];
        return view('admin.pages.subcategory.manage',compact('subcategorys','title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $categorys = category::latest()->get();
        $subcategory=subcategory::find($id);
        $title = 'Edit subcategory';
        return view('admin.pages.subcategory.edit',compact('subcategory','title','categorys'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(empty($id)){
            session()->flash('error', 'subcategory Not Found');
            return response()->json(['status'=>false, 'notFound'=>'true','error'=>'subcategory not found']);
        }
        // Validate the incoming request data
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048'

        ]);

        // Check if validation fails
        if ($validation->fails()) {
            return response()->json(['status'=>false, 'errors'=>$validation->errors()]);

        }
        else{

        // Create a new subcategory instance
        $subcategory = subcategory::find($id);
        $subcategory->name = $request->name;
        $subcategory->slug = $request->slug;

        if($request->hasfile('image')){
            $file =$request->file('image');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move('uploads/subcategory/',$filename);
            $subcategory->image =$filename;
        }

        $subcategory->categorys_id = $request->category;

        $subcategory->status = $request->status;
        $subcategory->showhome = $request->showhome;
        $subcategory->update(); // Save the subcategory to the database
        session()->flash('success', 'Updated Successfully');

        // Return a success response
        return response()->json(['status'=>true, 'success'=>'subcategory updated Successfully', 'data'=> $subcategory]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $subcategory = Subcategory::find($id);

        if (!$subcategory) {
            session()->flash('error', 'Subcategory Not Found');
            return response()->json(['status' => false, 'error' => 'Subcategory not found']);
        }

        // Delete the associated image file
        $imagePath = public_path('uploads/subcategory/' . $subcategory->image);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $subcategory->delete();
        session()->flash('success', 'Subcategory deleted successfully');
        return response()->json(['status' => true, 'success' => 'Subcategory deleted successfully']);
    }
}

