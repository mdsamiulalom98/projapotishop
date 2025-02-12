<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\News;

class NewsController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:news-list|news-create|news-edit|news-delete', ['only' => ['index','store']]);
        // $this->middleware('permission:news-create', ['only' => ['create','store']]);
        // $this->middleware('permission:news-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:news-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data =News::orderBy('id','DESC')->get();
        return view('backEnd.news.index',compact('data'));
    }
    public function create()
    {
        return view('backEnd.news.create');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'status' => 'required',
        ]);


        $input = $request->all();
        $input['status'] = $request->status?1:0;
        News::create($input);
        Toastr::success('Success','Data insert successfully');
        return redirect()->route('news.index');
    }

    public function edit($id)
    {
        $edit_data =News::find($id);
        return view('backEnd.news.edit',compact('edit_data'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);
        $update_data =News::find($request->id);
        $input = $request->all();
        $input['status'] = $request->status?1:0;
        $update_data->update($input);

        Toastr::success('Success','Data update successfully');
        return redirect()->route('news.index');
    }

    public function inactive(Request $request)
    {
        $inactive =News::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active =News::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
}
