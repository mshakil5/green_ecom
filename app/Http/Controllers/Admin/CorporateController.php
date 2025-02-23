<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Corporate;
use Illuminate\Http\Request;

class CorporateController extends Controller
{
    public function index()
    {
        $data = Corporate::all()->first();
        return view('admin.corporate.index',compact('data'));
    }

    public function update(Request $request)
    {

        // dd($request->all());

        $data = Corporate::find($request->codeid);
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable',
        ]);
        $data->title = $request->title;
        $data->description = $request->description;
        $data->save();
        return redirect()->back()->with('success', 'Corporate details updated successfully.');


    }
}
