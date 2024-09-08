<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\frontController;
use App\Http\Controllers\pageController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\subcategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;






/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/dashboard', function () {
//     return view('admin.dashboard');
// });

//------ Front-End Routes --------------
route::get('/',[frontController::class,'home'])->name('home');
route::get('/contact-us',[frontController::class,'contact'])->name('contact');
route::get('/pricing',[frontController::class,'pricing'])->name('pricing');
route::get('/dedicated-developers',[frontController::class,'developers'])->name('developers');
route::get('/about-us',[frontController::class,'about'])->name('about');
route::get('/blog',[frontController::class,'blog'])->name('blog');
route::get('/portfolio',[frontController::class,'portfolio'])->name('portfolio');
route::get('services/block-chain',[frontController::class,'blockChain'])->name('chain');
route::get('industry/e-commerce',[frontController::class,'ecommerce'])->name('ecommerce');


//------- Backend Routes -----------------


//------- Authentication -----------------

route::get('/login',[UserController::class,'index'])->name('admin.login');

route::post('/authenticate',[UserController::class,'authenticate'])->name('admin.authenticate');

route::get('/logout',[UserController::class,'logout'])->name('admin.logout');


//-------- Routes

Route::group(['prefix'=>'admin','middleware'=>'admin.auth'],function(){
     route::get('/dashboard',[RouteController::class,'dashboard'])->name('admin.dashboard');

     //------ user routes

     Route::group(['prefix'=>'user'],function(){

        route::get('/create',[UserController::class,'create'])->name('user.create');
        route::post('/store',[UserController::class,'store'])->name('user.store');
        route::get('/manage',[UserController::class,'show'])->name('user.show');
        route::get('/edit/{id}',[UserController::class,'edit'])->name('user.edit');
        route::post('/update/{id}',[UserController::class,'update'])->name('user.update');
        route::get('/delete/{id}',[UserController::class,'destroy'])->name('user.destroy');

     });


     //------ Category routes

     Route::group(['prefix'=>'category'],function(){

        route::get('/create',[CategoryController::class,'create'])->name('category.create');
        route::post('/store',[categoryController::class,'store'])->name('category.store');
        route::get('/manage',[categoryController::class,'show'])->name('category.show');
        route::get('/edit/{id}',[categoryController::class,'edit'])->name('category.edit');
        route::post('/update/{id}',[categoryController::class,'update'])->name('category.update');
        route::get('/delelte/{id}',[categoryController::class,'destroy'])->name('category.destroy');

        //-------- get-slug
        Route::get('/category/getslug', function(Request $request) {
            $slug = '';
            if(!empty($request->name)){
                $slug = Str::slug($request->name);
            }
            return response()->json(['status' => true, 'slug' => $slug]);
        })->name('category.slug');

     });


     Route::group(['prefix'=>'subcategory'],function(){

        route::get('/create',[subcategoryController::class,'create'])->name('subcategory.create');
        route::post('/store',[subcategoryController::class,'store'])->name('subcategory.store');
        route::get('/manage',[subcategoryController::class,'show'])->name('subcategory.show');
        route::get('/edit/{id}',[subcategoryController::class,'edit'])->name('subcategory.edit');
        route::post('/update/{id}',[subcategoryController::class,'update'])->name('subcategory.update');
        route::get('/delelte/{id}',[subcategoryController::class,'destroy'])->name('subcategory.destroy');

        //-------- get-slug
        Route::get('/subcategory/getslug', function(Request $request) {
            $slug = '';
            if(!empty($request->name)){
                $slug = Str::slug($request->name);
            }
            return response()->json(['status' => true, 'slug' => $slug]);
        })->name('subcategory.slug');

     });


     //----- pages routes

     Route::group(['prefix'=>'page'],function(){

        route::get('/create',[pageController::class,'create'])->name('page.create');
        route::post('/store',[pageController::class,'store'])->name('page.store');
        route::get('/manage',[pageController::class,'show'])->name('page.show');
        route::get('/edit/{id}',[pageController::class,'edit'])->name('page.edit');
        route::post('/update/{id}',[pageController::class,'update'])->name('page.update');
        route::get('/delelte/{id}',[pageController::class,'destroy'])->name('page.destroy');

        //-------- get-slug
        Route::get('/page/getslug', function(Request $request) {
            $slug = '';
            if(!empty($request->name)){
                $slug = Str::slug($request->name);
            }
            return response()->json(['status' => true, 'slug' => $slug]);
        })->name('page.slug');

     });

});





