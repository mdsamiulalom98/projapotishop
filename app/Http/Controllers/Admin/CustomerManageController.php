<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\CustomerProfit;
use App\Models\Customer;
use App\Models\SellerWithdraw;
use App\Models\IpBlock;
use App\Models\Expense;
use Carbon\Carbon;
use Toastr;
use Image;
use File;
use Auth;
use Hash;
class CustomerManageController extends Controller
{
    

    public function index(Request $request) {
        $show_data = Customer::where(['seller_type' => $request->type, 'status' => $request->status]);

        if ($request->keyword) {
            $show_data = $show_data->where(function ($query) use ($request) {
                $query->orWhere('phone', 'like', '%' . $request->keyword . '%')
                      ->orWhere('name', 'like', '%' . $request->keyword . '%');
            });
        }

        $show_data = $show_data->paginate(20);
        //return $show_data;
        return view('backEnd.customer.index', compact('show_data'));
    }

    public function rereport(Request $request) {
        $show_data = Customer::where(['seller_type' => $request->type, 'status' => $request->status]) ->withCount(['seller_orders as orderdetails_count' => function ($query) {
            $query->withCount('orderdetails');
        }])
        ->orderBy('orderdetails_count', 'DESC')->paginate(20);
        return view('backEnd.customer.rereport', compact('show_data'));
    }


    public function edit($id){
        $edit_data = Customer::find($id);
        return view('backEnd.customer.edit',compact('edit_data'));
    }
    
    public function update(Request $request){
        //return $request->all();
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
        ]);

        $input = $request->except('hidden_id');
        $update_data = Customer::find($request->hidden_id);
        // return $update_data;
         $input = $request->except('hidden_id');
         //return $input;
        // new password
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }

        // new image
        $image = $request->file('image');
        if($image){
            // image with intervention 
            $name =  time().'-'.$image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp',$name);
            $name = strtolower(preg_replace('/\s+/', '-', $name));
            $uploadpath = 'public/uploads/customer/';
            $imageUrl = $uploadpath.$name; 
            $img=Image::make($image->getRealPath());
            $img->encode('webp', 90);
            $width = 100;
            $height = 100;
            $img->height() > $img->width() ? $width=null : $height=null;
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($imageUrl);
            $input['image'] = $imageUrl;
            File::delete($update_data->image);
        }else{
            $input['image'] = $update_data->image;
        }

        $input['address'] = $request->input('address');

        $input['status'] = $request->status?'active':'pending';

        //return $input;
        $update_data->update($input);
        //return $update_data;

        Toastr::success('Success','Data update successfully');
        return redirect()->route('customers.index', ['type' => 1, 'status' => 'pending']);

    }

    public function withdraw($status,Request $request){
        $title = $status;
        $sellers = Customer::where(['status'=>'active'])->select('id','name','status')->get();
        $withdraws = SellerWithdraw::where(['status'=>$status]);
         if ($request->seller_id) {
            $withdraws = $withdraws->where('seller_id', $request->seller_id);
        }
        if ($request->start_date && $request->end_date) {
            $withdraws =$withdraws->whereBetween('created_at', [$request->start_date,$request->end_date]);
        }
        $withdraws = $withdraws->paginate(100);
        return view('backEnd.customer.withdraw',compact('title','withdraws','sellers'));
    }
    public function withdraw_change(Request $request){

        $seller = SellerWithdraw::find($request->id);
        $seller->status = $request->status;
        $seller->admin_note = $request->admin_note;
        $seller->save();
        $seller_name = Customer::select()->find($seller->seller_id);
        if($request->status == 'paid'){
            $balance = Customer::find($seller->seller_id);
            $balance->balance -= $seller->amount;
            $balance->withdraw += $seller->amount;
            //return $balance;
            $balance->save();
            
            $expense = new Expense();
            $expense->name = 'Withdraw paid of - '. $seller_name->name;
            $expense->expense_cat_id = 1;
            $expense->amount = $seller->amount;
            $expense->note = $request->admin_note;
            $expense->date = Carbon::now();
            $expense->status = 1;
            //return $expense;
             $expense->save();
        }
        Toastr::success('Success','Withdraw status change successfully');
        return redirect()->back();
    }

    public function slip($invoice_id){
        $slip_data = SellerWithdraw::where(['id'=>$invoice_id])->with('customer')->firstOrFail();
        return view('backEnd.customer.slip',compact('slip_data'));
    }
 
    public function inactive(Request $request){
        $inactive = Customer::find($request->hidden_id);
        $inactive->status = 'inactive';
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request){
        $active = Customer::find($request->hidden_id);
        $active->status = 'active';
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
    public function profile(Request $request){
        $profile = customer::with('orders', 'withdraws')->find($request->id);
        //return $profile;
        return view('backEnd.customer.profile',compact('profile'));
    }
    public function adminlog(Request $request){
        $customer = Customer::find($request->hidden_id);
        Auth::guard('customer')->loginUsingId($customer->id);
        return redirect()->route('customer.account');
    }
    public function ip_block(Request $request){
        $data = IpBlock::get();
        return view('backEnd.reports.ipblock',compact('data'));
    }
    public function ipblock_store(Request $request){

        $store_data = new IpBlock();
        $store_data->ip_no = $request->ip_no;
        $store_data->reason = $request->reason;
        $store_data->save();
        Toastr::success('Success','IP address add successfully');
        return redirect()->back();
    }
    public function ipblock_update(Request $request){
        $update_data = IpBlock::find($request->id);
        $update_data->ip_no = $request->ip_no;
        $update_data->reason = $request->reason;
        $update_data->save();
        Toastr::success('Success','IP address update successfully');
        return redirect()->back();
    }
    public function ipblock_destroy(Request $request){
        $delete_data = IpBlock::find($request->id)->delete();
        Toastr::success('Success','IP address delete successfully');
        return redirect()->back();
    }
}
