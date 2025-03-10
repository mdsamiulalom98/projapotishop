<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Productimage;
use App\Models\Productprice;
use App\Models\Productcolor;
use App\Models\Productsize;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Brand;
use App\Models\Color;
use App\Models\ProductVariable;
use App\Models\Size;
use Toastr;
use File;
use Str;
use Image;
use DB;

class ProductController extends Controller
{
    public function getSubcategory(Request $request)
    {
        $subcategory = DB::table("subcategories")
        ->where("category_id", $request->category_id)
        ->pluck('subcategoryName', 'id');
        return response()->json($subcategory);
    }
    public function getChildcategory(Request $request)
    {
        $childcategory = DB::table("childcategories")
        ->where("subcategory_id", $request->subcategory_id)
        ->pluck('childcategoryName', 'id');
        return response()->json($childcategory);
    }


    function __construct()
    {
         $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
         $this->middleware('permission:product-create', ['only' => ['create','store']]);
         $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        if($request->keyword){
            $data = Product::orderBy('id','DESC')->where('name', 'LIKE', '%' . $request->keyword . "%")->with('image','category')->paginate(50);
        }else{
            $data = Product::orderBy('id','DESC')->with('image','category')->paginate(50);
        }
        return view('backEnd.product.index',compact('data'));
    }
    public function create()
    {
        $categories = Category::where('parent_id','=','0')->where('status',1)->select('id','name','status')->with('childrenCategories')->get();
        $brands = Brand::where('status','1')->select('id','name','status')->get();
        $colors = Color::where('status','1')->get();
        $sizes = Size::where('status','1')->get();
        return view('backEnd.product.create',compact('categories','brands','colors','sizes'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'category_id' => 'required',
            'description' => 'required',
        ]);
        $last_id = Product::orderBy('id', 'desc')->select('id')->first();
        $last_id = $last_id?$last_id->id+1:1;
        $input = $request->except(['image','product_type','files','sizes','colors','purchase_prices','old_prices','new_prices','stocks','images','meta_image']);
        $input['slug'] = strtolower(preg_replace('/[\/\s]+/', '-', $request->slug));

        $input['status'] = $request->status?1:0;
        $input['topsale'] = $request->topsale?1:0;
        $input['free_shipping'] = $request->free_shipping?1:0;
        $input['product_code'] = 'P' . str_pad($last_id, 4, '0', STR_PAD_LEFT);

        $meta_image = $request->file('meta_image');
        if($meta_image){
            $name1 =  time().'-'.$meta_image->getClientOriginalName();
            $name1 = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp',$name1);
            $name1 = strtolower(preg_replace('/\s+/', '-', $name1));
            $uploadPath1 = 'public/uploads/product/';
            $imageUrl1 = $uploadPath1.$name1;
            $img1=Image::make($meta_image->getRealPath());
            $img1->encode('webp', 90);
            $width = 210;
            $height = 210;
            $img1->height() > $img1->width() ? $width=null : $height=null;
            $img1->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img1->save($imageUrl1);
        }else{
            $imageUrl1 = NULL;
        }



    $input['meta_image'] = $imageUrl1;
    $save_data = Product::create($input);



        $pro_image = $request->file('image');
        if($pro_image){
            foreach ($pro_image as $key => $image) {
                $name =  time().'-'.$image->getClientOriginalName();
                $name = strtolower(preg_replace('/\s+/', '-', $name));
                $uploadPath = 'public/uploads/product/';
                $image->move($uploadPath,$name);
                $imageUrl =$uploadPath.$name;

                $pimage             = new Productimage();
                $pimage->product_id = $save_data->id;
                $pimage->image      = $imageUrl;
                $pimage->save();
            }
        }

       $new_prices = array_filter($request->new_prices);
        if (!empty($new_prices)) {
            $save_data->new_price = $new_prices[0];
            $save_data->save();
        }

        if($request->stocks){
            $size      = $request->sizes;
            $color      = $request->colors;
            $stocks      = array_filter($request->stocks);
            $purchase   = $request->purchase_prices;
            $old_price  = $request->old_prices;
            $new_price  = $request->new_prices;
            $images     = $request->file('images');
            if(is_array($stocks)){
                foreach($stocks as $key=>$stock)
                {

                        if(!empty($images[$key])){
                            $image = $images[$key];
                            $name =  time().'-'.$image->getClientOriginalName();
                            $name = strtolower(preg_replace('/\s+/', '-', $name));
                            $uploadPath = 'public/uploads/product/';
                            $image->move($uploadPath,$name);
                            $imageUrl =$uploadPath.$name;
                        }else{
                            $imageUrl = NULL;
                        }
                        $variable= new ProductVariable();
                        $variable->product_id       = $save_data->id;
                        $variable->size             = $size?$size[$key]:NULL;
                        $variable->color            = $color?$color[$key]:NULL;
                        $variable->purchase_price   = $purchase[$key];
                        $variable->old_price        = $old_price?$old_price[$key] : NULL;
                        $variable->new_price        = $new_price[$key];
                        $variable->stock            = $stock;
                        $variable->image            = $imageUrl;
                        $variable->save();

                }
            }
        }

        Toastr::success('Success','Data insert successfully');
        return redirect()->route('products.index');
    }

    public function edit($id)
    {
        $edit_data = Product::with('images')->find($id);
        $categories = Category::where('parent_id','=','0')->where('status',1)->select('id','name','status')->get();
        $categoryId = Product::find($id)->category_id;
        $subcategoryId = Product::find($id)->subcategory_id;
        $subcategory = Subcategory::where('category_id', '=', $categoryId)->select('id','subcategoryName','status')->get();
        $childcategory = Childcategory::where('subcategory_id', '=', $subcategoryId)->select('id', 'childcategoryName', 'status')->get();
        $brands = Brand::where('status','1')->select('id','name','status')->get();
        $colors = Color::where('status','1')->get();
        $sizes = Size::where('status','1')->get();
        $variables = ProductVariable::where('product_id',$id)->get();
        // return $variables;
        return view('backEnd.product.edit',compact('edit_data','categories', 'subcategory', 'childcategory', 'brands','sizes', 'colors','variables'));
    }
    public function price_edit()
    {
        $products = DB::table('products')->select('id','name','status','old_price','new_price','stock')->where('status',1)->get();;
        return view('backEnd.product.price_edit',compact('products'));
    }

    public function update(Request $request)
    {
        // return $request->all();
       $this->validate($request, [
            'name' => 'required',
            'category_id' => 'required',
            'description' => 'required',
        ]);
         //return $request->all();
        $update_data = Product::find($request->id);
        // return $update_data;
        $input = $request->except(['image','product_type','files','sizes','colors','purchase_prices','old_prices','new_prices','stocks','images','up_id','up_sizes','up_colors','up_purchase_prices','up_old_prices','up_new_prices','up_stocks','up_images']);
        $last_id = Product::orderBy('id', 'desc')->select('id')->first();
        $input['slug'] = strtolower(preg_replace('/[\/\s]+/', '-', $request->slug));
        $input['status'] = $request->status?1:0;
        $input['topsale'] = $request->topsale?1:0;
        $input['free_shipping'] = $request->free_shipping?1:0;
        //meta image
        $meta_image = $request->file('meta_image');
        if($meta_image){
            // image with intervention
            $name1 =  time().'-'.$meta_image->getClientOriginalName();
            $name1 = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp',$name1);
            $name1 = strtolower(preg_replace('/\s+/', '-', $name1));
            $uploadpath1 = 'public/uploads/category/';
            $imageUrl1 = $uploadpath1.$name1;
            $img=Image::make($meta_image->getRealPath());
            $img->encode('webp', 90);
            $width = "";
            $height = "";
            $img->height() > $img->width() ? $width=null : $height=null;
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($imageUrl1);
            $input['meta_image'] = $imageUrl1;
            File::delete($update_data->meta_image);

        }else{
            $input['meta_image'] = $update_data->meta_image;
        }
        $update_data->update($input);

       if($update_data->type == 0){
           $new_prices = array_filter($request->up_new_prices);
            if (!empty($new_prices)) {
                $update_data->new_price = $new_prices[0];
                $update_data->save();
            }
        }

        // image dynamic
        $images = $request->file('image');
        if($images){
            foreach ($images as $key => $image) {
                $name =  time().'-'.$image->getClientOriginalName();
                $name = strtolower(preg_replace('/\s+/', '-', $name));
                $uploadPath = 'public/uploads/product/';
                $image->move($uploadPath,$name);
                $imageUrl =$uploadPath.$name;

                $pimage             = new Productimage();
                $pimage->product_id = $update_data->id;
                $pimage->image      = $imageUrl;
                $pimage->save();
            }
        }

        if($request->up_id){
            $update_ids = array_filter($request->up_id);
            $up_color   = $request->up_colors;
            $up_size    = $request->up_sizes;
            $up_size    = $request->up_sizes;
            $up_stock   = $request->up_stocks;
            $up_purchase   = $request->up_purchase_prices;
            $up_old_price  = $request->up_old_prices;
            $up_new_price  = $request->up_new_prices;
            $images     = $request->file('up_images');
            if($update_ids){
                foreach($update_ids as $key=>$update_id)
                {
                    $upvariable=  ProductVariable::find($update_id);
                       if(isset($images[$key])){
                            $image = $images[$key];
                            $name =  time().'-'.$image->getClientOriginalName();
                            $name = strtolower(preg_replace('/\s+/', '-', $name));
                            $uploadPath = 'public/uploads/product/';
                            $image->move($uploadPath,$name);
                            $imageUrl =$uploadPath.$name;
                            File::delete($upvariable->image);
                        }else{
                           $imageUrl = $upvariable->image;
                        }


                        $upvariable->product_id       = $update_data->id;
                        $upvariable->size             = $up_size?$up_size[$key]:NULL;
                        $upvariable->color            = $up_color?$up_color[$key]:NULL;
                        $upvariable->purchase_price   = $up_purchase[$key];
                        $upvariable->old_price        = $up_old_price?$up_old_price[$key] : NULL;
                        $upvariable->new_price        = $up_new_price[$key];
                        $upvariable->stock            = $up_stock[$key];
                        $upvariable->image            = $imageUrl;
                        $upvariable->save();
                    }
                }
            }


        if($request->stocks){
            $size       = $request->sizes;
            $color      = $request->colors;
            $stocks     = array_filter($request->stocks);
            $purchase   = $request->purchase_prices;
            $old_price  = $request->old_prices;
            $new_price  = $request->new_prices;
            $images     = $request->file('images');
            if(is_array($stocks)){
                foreach($stocks as $key=>$stock)
                {

                        if(!empty($images[$key])){
                            $image = $images[$key];
                            $name =  time().'-'.$image->getClientOriginalName();
                            $name = strtolower(preg_replace('/\s+/', '-', $name));
                            $uploadPath = 'public/uploads/product/';
                            $image->move($uploadPath,$name);
                            $imageUrl =$uploadPath.$name;
                        }else{
                            $imageUrl = NULL;
                        }

                        $variable= new ProductVariable();
                        $variable->product_id       = $update_data->id;
                        $variable->size             = $size?$size[$key]:NULL;
                        $variable->color            = $color?$color[$key]:NULL;
                        $variable->purchase_price   = $purchase[$key];
                        $variable->old_price        = $old_price?$old_price[$key] : NULL;
                        $variable->new_price        = $new_price[$key];
                        $variable->stock            = $stock;
                        $variable->image            = $imageUrl;
                        $variable->save();

                }
            }
        }

        Toastr::success('Success','Data update successfully');
        return redirect()->route('products.index');
    }

    public function inactive(Request $request)
    {
        $inactive = Product::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = Product::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $delete_data = Product::find($request->hidden_id);
        foreach($delete_data->variables as $variable){
            File::delete($variable->image);
            $variable->delete();
        }
        foreach($delete_data->images as $pimage){
            File::delete($pimage->image);
            $pimage->delete();
        }
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }
    public function imgdestroy(Request $request)
    {
        $delete_data = Productimage::find($request->id);
        File::delete($delete_data->image);
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }
    public function variable_imgdestroy(Request $request)
    {
        $delete_data = ProductVariable::find($request->id);
        File::delete($delete_data->image);
        $delete_data->image = '';
        $delete_data->save();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }
    public function pricedestroy(Request $request)
    {
        $delete_data = ProductVariable::find($request->id);
        File::delete($delete_data->image);
        $delete_data->delete();
        Toastr::success('Success','Product price delete successfully');
        return redirect()->back();
    }
    public function update_deals(Request $request){
        $products = Product::whereIn('id', $request->input('product_ids'))->update(['topsale' => $request->status]);
        return response()->json(['status'=>'success','message'=>'Hot deals product status change']);
    }
    public function update_feature(Request $request){
        $products = Product::whereIn('id', $request->input('product_ids'))->update(['feature_product' => $request->status]);
        return response()->json(['status'=>'success','message'=>'Feature product status change']);
    }
    public function update_status(Request $request){
        $products = Product::whereIn('id', $request->input('product_ids'))->update(['status' => $request->status]);
        return response()->json(['status'=>'success','message'=>'Product status change successfully']);
    }
}
