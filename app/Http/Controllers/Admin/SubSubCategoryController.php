<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use Illuminate\Support\Str;

class SubSubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::select('id', 'name','category_id')->get();
        $data = SubSubCategory::orderby('id','DESC')->get();
        $categories = Category::select('id', 'name')->orderby('id', 'DESC')->get();
        return view('admin.sub_sub_category.index', compact('data','subCategories','categories'));
    }

    public function store(Request $request)
    {
        if(empty($request->name)){
            $message ="<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>Please fill \"Sub Sub category name \" field..!</b></div>";
            return response()->json(['status'=> 303,'message'=>$message]);
            exit();
        }
        if(empty($request->category_id)){
            $message ="<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>Please fill \"category name \" field..!</b></div>";
            return response()->json(['status'=> 303,'message'=>$message]);
            exit();
        }

        if(empty($request->sub_category_id)){
            $message ="<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>Please fill \" Subcategory name \" field..!</b></div>";
            return response()->json(['status'=> 303,'message'=>$message]);
            exit();
        }
        $chkname = SubSubCategory::where('name',$request->name)->where('category_id',$request->category_id)->where('sub_category_id',$request->sub_category_id)->first();
        if($chkname){
            $message ="<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>This sub sub category already added under this category.</b></div>";
            return response()->json(['status'=> 303,'message'=>$message]);
            exit();
        }
        
        $data = new SubSubCategory;
        $data->name = $request->name;
        $data->category_id = $request->category_id;
        $data->sub_category_id = $request->sub_category_id;
        $data->description = $request->description;
        $category = Category::find($request->category_id);
        $subCategory = SubCategory::find($request->sub_category_id);
        $data->slug = Str::slug($request->name . '-' . $category->name . '-' . $subCategory->name);
        $data->created_by = auth()->id(); 
        
        if ($data->save()) {
            $message ="<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>Data Create Successfully.</b></div>";
            return response()->json(['status'=> 300,'message'=>$message]);
        }else{
            return response()->json(['status'=> 303,'message'=>'Server Error!!']);
        }
    }

    public function edit($id)
    {
        $where = [
            'id'=>$id
        ];
        $info = SubSubCategory::where($where)->get()->first();
        return response()->json($info);
    }

    public function update(Request $request)
    {
        if(empty($request->name)){
            $message ="<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>Please fill \"Sub Sub category name \" field..!</b></div>";
            return response()->json(['status'=> 303,'message'=>$message]);
            exit();
        }
        if(empty($request->sub_category_id)){
            $message ="<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>Please fill \"Sub Sub category name \" field..!</b></div>";
            return response()->json(['status'=> 303,'message'=>$message]);
            exit();
        }

        $duplicatename = SubSubCategory::where('name',$request->name)->where('category_id',$request->category_id)->where('sub_category_id',$request->sub_category_id)->where('id','!=', $request->codeid)->first();
        if($duplicatename){
            $message ="<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>This sub sub category already added under this category.</b></div>";
            return response()->json(['status'=> 303,'message'=>$message]);
            exit();
        }

         $data = SubSubCategory::find($request->codeid);
         $data->name = $request->name;     
         $category = Category::find($request->category_id);
         $subCategory = SubCategory::find($request->sub_category_id);
         $data->slug = Str::slug($request->name . '-' . $category->name . '-' . $subCategory->name);
         $data->category_id = $request->category_id;
         $data->sub_category_id = $request->sub_category_id;
         $data->description = $request->description;        
         $data->updated_by = auth()->id();

          if ($data->save()) {
            $message = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>Data Updated Successfully.</b></div>";
            return response()->json(['status' => 300, 'message' => $message]);
        } else {
            $message = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>Failed to update data. Please try again.</b></div>";
            return response()->json(['status' => 303, 'message' => $message]);
        }

    }

    public function delete($id)
    {
        $data = SubSubCategory::find($id);
        
        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Not found.'], 404);
        }

        if ($data->delete()) {
            return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to delete.'], 500);
        }
    }

    public function toggleStatus(Request $request)
    {
        $subSubCategory = SubSubCategory::find($request->sub_category_id);
        if (!$subSubCategory) {
            return response()->json(['status' => 404, 'message' => 'Not found']);
        }

        $subSubCategory->status = $request->status;
        $subSubCategory->save();

        return response()->json(['status' => 200, 'message' => 'Status updated successfully']);
    }
}
