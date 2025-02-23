<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:paymentmethod-list|paymentmethod-create|paymentmethod-edit|paymentmethod-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:paymentmethod-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:paymentmethod-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:paymentmethod-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = PaymentMethod::orderBy('id', 'DESC')->get();
        return view('backEnd.paymentmethod.index', compact('data'));
    }

    public function create()
    {
        return view('backEnd.paymentmethod.create');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        // image with intervention
        $file = $request->file('image');
        $name = time() . $file->getClientOriginalName();
        $uploadPath = 'public/uploads/paymentmethod/';
        $file->move($uploadPath, $name);
        $fileUrl = $uploadPath . $name;

        $input = $request->all();
        $input['slug'] = strtolower(preg_replace('/\s+/', '-', $request->name));
        $input['slug'] = str_replace('/', '', $input['slug']);
        $input['status'] = $request->status ? 1 : 0;
        $input['image'] = $fileUrl;
        PaymentMethod::create($input);
        Toastr::success('Success', 'Data insert successfully');
        return redirect()->route('paymentmethods.index');
    }

    public function edit($id)
    {
        $edit_data = PaymentMethod::find($id);
        return view('backEnd.paymentmethod.edit', compact('edit_data'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $update_data = PaymentMethod::find($request->id);
        $input = $request->all();
        $image = $request->file('image');
        if ($image) {
            // image with intervention
            $file = $request->file('image');
            $name = time() . $file->getClientOriginalName();
            $uploadPath = 'public/uploads/paymentmethod/';
            $file->move($uploadPath, $name);
            $fileUrl = $uploadPath . $name;
            $input['image'] = $fileUrl;
            File::delete($update_data->image);
        } else {
            $input['image'] = $update_data->image;
        }
        $input['slug'] = strtolower(preg_replace('/\s+/', '-', $request->name));
        $input['slug'] = str_replace('/', '', $input['slug']);
        $input['status'] = $request->status ? 1 : 0;
        $update_data->update($input);

        Toastr::success('Success', 'Data update successfully');
        return redirect()->route('paymentmethods.index');
    }

    public function inactive(Request $request)
    {
        $inactive = PaymentMethod::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success', 'Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = PaymentMethod::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success', 'Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $delete_data = PaymentMethod::find($request->hidden_id);
        $delete_data->delete();
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->back();
    }

    public function update_order(Request $request)
    {
        $order = $request->input('order');

        foreach ($order as $sortOrder => $id) {
            PaymentMethod::where('id', $id)->update(['sort' => $sortOrder + 1]);
        }

        return response()->json(['success' => true]);
    }
}
