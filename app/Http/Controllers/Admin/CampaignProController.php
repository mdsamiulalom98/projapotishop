<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Str;
use App\Models\CampaignPro;
use App\Models\Product;

class CampaignProController extends Controller
{
    public function index(Request $request)
    {
        $show_data = CampaignPro::orderBy('id', 'DESC')->get();
        // return $show_data;
        return view('backEnd.proCampaign.index', compact('show_data'));
    }
    public function create()
    {
        $products = Product::where(['status' => 1])->select('id', 'name', 'status')->get();
        return view('backEnd.proCampaign.create', compact('products'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'name' => 'required',
            'bgcolor' => 'required',
            'status' => 'required',
        ]);

        $input = $request->except('product_ids');
        // image with intervention
        $image = $request->file('image');
        if ($image) {
            $name = time() . '-' . $image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name);
            $name = strtolower(preg_replace('/\s+/', '-', $name));
            $uploadpath = 'public/uploads/banner/';
            $imageUrl = $uploadpath . $name;
            $img = Image::make($image->getRealPath());
            $img->encode('webp', 90);
            $width = "";
            $height = "";
            $img->height() > $img->width() ? $width = null : $height = null;
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($imageUrl);
        } else {
            $imageUrl = null;
        }

        // image2 with intervention
        $file = $request->file('image2');
        $name2 = time() . $file->getClientOriginalName();
        $uploadPath2 = 'public/uploads/banner/';
        $file->move($uploadPath2, $name2);
        $fileUrl = $uploadPath2 . $name2;

        $input['image'] = $imageUrl;
        $input['image2'] = $fileUrl;
        $input['slug'] = strtolower(Str::slug($request->name));
        $input['front_view'] = $request->front_view ? 1 : 0;
        $input['status'] = $request->status ? 1 : 0;
        $campaign = CampaignPro::create($input);

        foreach ($request->product_ids as $product_id) {
            $product = Product::find($product_id);
            $product->campaign_id = $campaign->id;
            $product->save();
        }
        Toastr::success('Success', 'Data insert successfully');
        return redirect()->route('proCampaign.index');
    }

    public function edit($id)
    {
        $edit_data = CampaignPro::find($id);
        $select_products = Product::where('campaign_id', $id)->get();
        $products = Product::where(['status' => 1])->select('id', 'name', 'status')->get();
        return view('backEnd.proCampaign.edit', compact('edit_data', 'products', 'select_products'));
    }

    public function update(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'date' => 'required',
            'bgcolor' => 'required',
            'name' => 'required',
        ]);
        $update_data = CampaignPro::find($request->hidden_id);
        $input = $request->all();
        $input = $request->except('hidden_id', 'product_ids');


        $image = $request->file('image');
        if ($image) {
            // image with intervention
            $name = time() . '-' . $image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name);
            $name = strtolower(preg_replace('/\s+/', '-', $name));
            $uploadpath = 'public/uploads/category/';
            $imageUrl = $uploadpath . $name;
            $img = Image::make($image->getRealPath());
            $img->encode('webp', 90);
            $width = "";
            $height = "";
            $img->height() > $img->width() ? $width = null : $height = null;
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            File::delete($update_data->image);
            $img->save($imageUrl);
            $input['image'] = $imageUrl;
        } else {
            $input['image'] = $update_data->image;
        }

        $image2 = $request->file('image2');
        if ($image2) {
            // image with intervention
            $file = $request->file('image2');
            $name2 = time() . $file->getClientOriginalName();
            $uploadPath2 = 'public/uploads/banner/';
            $file->move($uploadPath2, $name2);
            $fileUrl = $uploadPath2 . $name2;
            $input['image2'] = $fileUrl;
            File::delete($update_data->image2);
        } else {
            $input['image2'] = $update_data->image2;
        }



        $input['slug'] = strtolower(Str::slug($request->name));
        $input['front_view'] = $request->front_view ? 1 : 0;
        $input['status'] = $request->status ? 1 : 0;
        // return $input;
        $update_data->update($input);
        // return $update_data;

        foreach ($request->product_ids as $product_id) {
            $product = Product::find($product_id);
            $product->campaign_id = $update_data->id;
            $product->save();
        }

        Toastr::success('Success', 'Data update successfully');
        return redirect()->route('proCampaign.index');
    }

    public function inactive(Request $request)
    {
        $inactive = CampaignPro::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success', 'Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = CampaignPro::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success', 'Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {

        $delete_data = CampaignPro::find($request->hidden_id);
        $delete_data->delete();

        $campaign = Product::whereNotNull('campaign_id')->get();
        foreach ($campaign as $key => $value) {
            $product = Product::find($value->id);
            $product->campaign_id = null;
            $product->save();
        }
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->back();
    }
}
