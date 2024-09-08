<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class pageController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorys = category::latest()->get();
        $title = 'Add page';
        return view('admin.pages.page.create',compact('title','categorys'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate the incoming request data
    $validation = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'slug' => 'required|unique:pages',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048'

    ]);

    // Check if validation fails
    if ($validation->fails()) {
        return response()->json(['status'=>false, 'errors'=>$validation->errors()]);
    }
    else{

    // Create a new page instance
    $page = new page();
    $page->name = $request->name;
    $page->slug = $request->slug;

    if($request->hasfile('image')){
        $file =$request->file('image');
        $filename = time().'.'.$file->getClientOriginalExtension();
        $file->move('uploads/page/',$filename);
        $page->image =$filename;
    }

    $page->categorys_id = $request->category;

    $page->showhome = $request->showhome;
    $page->status = $request->status;
    $page->save(); // Save the page to the database

    session()->flash('success', 'page created Successfully');

    // Return a success response
    return response()->json(['status'=>true, 'success'=>'page created Successfully', 'data'=> $page]);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $pages = page::select('pages.*','categorys.name as categoryName')->latest('pages.id')
        ->leftjoin('categorys','categorys.id','pages.categorys_id');
        if(!empty($request->get('keyword'))){
            $pages= $pages->where('name','like','%'.$request->get('keyword').'%');
        }

        $pages = $pages->paginate(8);
        $title = 'page List';
        $data = [$pages, $title];
        return view('admin.pages.page.manage',compact('pages','title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $categorys = category::latest()->get();
        $page=page::find($id);
        $title = 'Edit page';
        return view('admin.pages.page.edit',compact('page','title','categorys'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(empty($id)){
            session()->flash('error', 'page Not Found');
            return response()->json(['status'=>false, 'notFound'=>'true','error'=>'page not found']);
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

        // Create a new page instance
        $page = page::find($id);
        $page->name = $request->name;
        $page->slug = $request->slug;

        if($request->hasfile('image')){
            $file =$request->file('image');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move('uploads/page/',$filename);
            $page->image =$filename;
        }

        $page->categorys_id = $request->category;

        $page->status = $request->status;
        $page->showhome = $request->showhome;
        $page->update(); // Save the page to the database
        session()->flash('success', 'Updated Successfully');

        // Return a success response
        return response()->json(['status'=>true, 'success'=>'page updated Successfully', 'data'=> $page]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $page = page::find($id);

        if (!$page) {
            session()->flash('error', 'page Not Found');
            return response()->json(['status' => false, 'error' => 'page not found']);
        }

        // Delete the associated image file
        $imagePath = public_path('uploads/page/' . $page->image);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $page->delete();
        session()->flash('success', 'page deleted successfully');
        return response()->json(['status' => true, 'success' => 'page deleted successfully']);
    }
}

