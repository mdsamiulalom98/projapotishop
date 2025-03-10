<?php

namespace App\Http\Controllers\Frontend;
use shurjopayv2\ShurjopayLaravelPackage8\Http\Controllers\ShurjopayController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Gloudemans\Shoppingcart\Facades\Cart;
use Intervention\Image\Facades\Image;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\ShippingCharge;
use App\Models\PaymentGateway;
use App\Models\SellerWithdraw;
use App\Models\GeneralSetting;
use App\Models\PaymentMethod;
use App\Models\OrderDetails;
use App\Models\SmsGateway;
use App\Models\CouponCode;
use App\Models\Shipping;
use App\Models\District;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Order;
use Carbon\Carbon;

class CustomerController extends Controller
{
    function __construct()
    {
        $this->middleware('customer', ['except' => ['register', 'customer_coupon', 'coupon_remove', 'store', 'verify', 'resendotp', 'account_verify', 'login', 'signin', 'pending', 'logout', 'checkout', 'forgot_password', 'forgot_verify', 'forgot_reset', 'forgot_store', 'forgot_resend', 'order_save', 'order_otp', 'order_success', 'order_track', 'order_track_result', 'customer_commision']]);
    }
    public function customer_coupon(Request $request)
    {
        $findcoupon = CouponCode::where('coupon_code', $request->coupon_code)->first();
        if ($findcoupon == NULL) {
            Toastr::error('Opps! your entered promo code is not valid');
            return back();
        } else {
            $currentdata = date('Y-m-d');
            $expiry_date = $findcoupon->expiry_date;
            if ($currentdata <= $expiry_date) {
                $totalcart = Cart::instance('shopping')->subtotal();
                $totalcart = str_replace('.00', '', $totalcart);
                $totalcart = str_replace(',', '', $totalcart);
                if ($totalcart >= $findcoupon->buy_amount) {
                    if ($totalcart >= $findcoupon->buy_amount) {
                        if ($findcoupon->offer_type == 1) {
                            $discountammount = (($totalcart * $findcoupon->amount) / 100);
                            Session::forget('coupon_amount');
                            Session::put('coupon_amount', $discountammount);
                            Session::put('coupon_used', $findcoupon->coupon_code);
                        } else {
                            Session::put('coupon_amount', $findcoupon->amount);
                            Session::put('coupon_used', $findcoupon->coupon_code);
                        }
                        Toastr::success('Success! your promo code accepted');
                        return back();
                    }

                } else {
                    Toastr::error('You need to buy a minimum of ' . $findcoupon->buy_amount . ' Taka to get the offer');
                    return back();
                }
            } else {
                Toastr::error('Opps! Sorry your promo code date expaire');
                return back();
            }
        }
    }


    public function commisions()
    {
        $userId = Auth::guard('customer')->user()->id;
        $commissions = Order::where(['reseller_id' => $userId, 'order_status' => 6])->latest()->paginate(10);
        $totalCommission = Order::where(['reseller_id' => $userId, 'order_status' => 6])->sum('commision');

        return view('frontEnd.layouts.customer.commisions', compact('commissions', 'totalCommission'));
    }
    // withdraw system start
    public function withdraw()
    {

        $withdraws = SellerWithdraw::where(['seller_id' => Auth::guard('customer')->user()->id])->latest()->paginate(20);
        return view('frontEnd.layouts.customer.withdraw', compact('withdraws'));
    }

    public function withdraw_request(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required',
            'receive' => 'required',
            'method' => 'required',
            'password' => 'required',
        ]);
        $pending_amount = SellerWithdraw::where(['status' => 'pending', 'seller_id' => Auth::guard('customer')->user()->id])->sum('amount');
        $balance_check = (Auth::guard('customer')->user()->balance - ($request->amount + $pending_amount));

        if (!Hash::check($request->password, Auth::guard('customer')->user()->password)) {
            Toastr::error('Your password is wrong', 'Failed');
            return redirect()->back();
        }
        if (Auth::guard('customer')->user()->balance < ($request->amount + $pending_amount)) {
            Toastr::error('Withdraw balance unsificient');
            return redirect()->back();
        }

        if ($request->amount < 500) {
            Toastr::error('Your request amount must be need at least 500 tk');
            return redirect()->back();
        }

        $withdraw = new SellerWithdraw();
        $withdraw->seller_id = Auth::guard('customer')->user()->id;
        $withdraw->amount = $request->amount;
        $withdraw->receive = $request->receive;
        $withdraw->method = $request->method;
        $withdraw->note = $request->note;
        $withdraw->request_date = Carbon::now();
        $withdraw->status = 'pending';
        $withdraw->save();

        Toastr::success('Withdraw request send successfully', 'success');
        return redirect()->back();
    }
    // withdraw system end


    public function coupon_remove(Request $request)
    {
        Session::forget('coupon_amount');
        Session::forget('coupon_used');
        Session::forget('discount');
        Toastr::success('Success', 'Your coupon remove successfully');
        return back();

    }

    public function review(Request $request)
    {
        $this->validate($request, [
            'ratting' => 'required',
            'review' => 'required',
        ]);

        // data save
        $review = new Review();
        $review->name = Auth::guard('customer')->user()->name ? Auth::guard('customer')->user()->name : 'N / A';
        $review->email = Auth::guard('customer')->user()->email ? Auth::guard('customer')->user()->email : 'N / A';
        $review->product_id = $request->product_id;
        $review->review = $request->review;
        $review->ratting = $request->ratting;
        $review->customer_id = Auth::guard('customer')->user()->id;
        $review->status = 'pending';
        $review->save();

        Toastr::success('Thanks, Your review send successfully', 'Success!');
        return redirect()->back();
    }

    public function login()
    {
        return view('frontEnd.layouts.customer.login');
    }

    public function signin(Request $request)
    {
        $auth_check = Customer::where('phone', $request->phone)->first();
        if ($auth_check) {
            if ($auth_check->status == 'active') {
                if (Auth::guard('customer')->attempt(['phone' => $request->phone, 'password' => $request->password])) {
                    Toastr::success('You are login successfully', 'success!');
                    if (Cart::instance('shopping')->count() > 0) {
                        return redirect()->route('customer.account');
                    }
                    return redirect()->intended('customer/account');
                }
                Toastr::error('message', 'Opps! your phone or password wrong');
                return redirect()->back();


            }
            return redirect()->route('customer.pending');


        } else {
            Toastr::error('message', 'Sorry! You have no account');
            return redirect()->back();
        }
    }

    public function pending()
    {
        return view('frontEnd.layouts.customer.pending');
    }
    public function register()
    {
        return view('frontEnd.layouts.customer.register');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|unique:customers',
            'seller_type' => 'required',
            'password' => 'required|min:6'
        ]);
        $sms_gateway = SmsGateway::where('status', 1)->first();

        $resellerId = $this->resellerGenerate();
        $verify = rand(111111, 999999);
        $verify = $sms_gateway->register_verify == 1 ? $verify : 1;
        $status = $sms_gateway->register_verify == 1 ? 'pending' : 'active';
        $store = new Customer();
        $store->name = $request->name;
        $store->slug = strtolower(Str::slug($request->name));
        $store->phone = $request->phone;
        $store->email = $request->email;
        $store->seller_type = $request->seller_type;
        $store->seller_request = 0;
        $store->password = bcrypt($request->password);
        $store->verify = $request->seller_type == 1 ? 1 : $verify;
        $store->status = $request->seller_type == 1 ? 'pending' : $status;
        $store->reseller_id = $request->seller_type == 1 ? $resellerId : NULL;
        $store->save();

        if ($store->seller_type == 1) {
            Toastr::success('Success', 'Your Reseller Account Create Successfully');
            return redirect()->route('customer.pending');
        }
        if ($sms_gateway->register_verify == 1) {
            session::put('verify_phone', $store->phone);
            $site_setting = GeneralSetting::where('status', 1)->first();
            session()->put('otp_sent_time', now()->timestamp);
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "number" => $store->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "message" => "Dear $store->name!\r\nYour account verify OTP is $store->verify \r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);

            return redirect()->route('customer.verify');
        } else {
            Toastr::success('Success', 'Your Reseller Account Create Successfully');
            return redirect()->route('customer.login');
        }

    }
    public function verify()
    {
        return view('frontEnd.layouts.customer.verify');
    }
    public function resendotp(Request $request)
    {
        session()->put('otp_sent_time', now()->timestamp);
        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();
        $customer_info->verify = rand(1111, 9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where('status', 1)->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "number" => $customer_info->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "message" => "Dear $customer_info->name!\r\nYour account verify OTP is $customer_info->verify \r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);

        }
        session()->put('otp_sent_time', now()->timestamp);
        Toastr::success('Success', 'Resend code send successfully');
        return redirect()->back();
    }
    public function account_verify(Request $request)
    {
        $this->validate($request, [
            'otp' => 'required',
        ]);
        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();
        if ($customer_info->verify != $request->otp) {
            Toastr::error('Failed', 'Your OTP not match');
            return redirect()->back();
        }

        $customer_info->verify = 1;
        $customer_info->status = 'active';
        $customer_info->save();

        session::forget('verify_phone');
        Toastr::success('Success', 'Your Account verified successfully');
        Auth::guard('customer')->loginUsingId($customer_info->id);
        return redirect()->route('customer.account');
    }
    public function forgot_password()
    {
        return view('frontEnd.layouts.customer.forgot_password');
    }

    public function forgot_verify(Request $request)
    {
        $customer_info = Customer::where('phone', $request->phone)->first();
        if (!$customer_info) {
            Toastr::error('Your phone number not found');
            return back();
        }
        $customer_info->forgot = rand(1111, 9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where(['status' => 1, 'forget_pass' => 1])->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "number" => $customer_info->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "message" => "Dear $customer_info->name!\r\nYour forgot password verify OTP is $customer_info->forgot \r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        }

        session::put('verify_phone', $request->phone);
        Toastr::success('Your account register successfully');
        return redirect()->route('customer.forgot.reset');
    }

    public function forgot_resend(Request $request)
    {
        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();
        $customer_info->forgot = rand(1111, 9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where(['status' => 1])->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "number" => $customer_info->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "message" => "Dear $customer_info->name!\r\nYour forgot password verify OTP is $customer_info->forgot \r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);

        }

        Toastr::success('Success', 'Resend code send successfully');
        return redirect()->back();
    }
    public function forgot_reset()
    {
        if (!Session::get('verify_phone')) {
            Toastr::error('Something wrong please try again');
            return redirect()->route('customer.forgot.password');
        }
        ;
        return view('frontEnd.layouts.customer.forgot_reset');
    }
    public function forgot_store(Request $request)
    {

        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();

        if ($customer_info->forgot != $request->otp) {
            Toastr::error('Success', 'Your OTP not match');
            return redirect()->back();
        }

        $customer_info->forgot = 1;
        $customer_info->password = bcrypt($request->password);
        $customer_info->save();
        if (Auth::guard('customer')->attempt(['phone' => $customer_info->phone, 'password' => $request->password])) {
            Session::forget('verify_phone');
            Toastr::success('You are login successfully', 'success!');
            return redirect()->intended('customer/account');
        }
    }
    public function account()
    {
        return view('frontEnd.layouts.customer.account');
    }
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        Toastr::success('You are logout successfully', 'success!');
        return redirect()->route('customer.login');
    }
    public function checkout()
    {
        $paymentmethods = PaymentMethod::where(['status' => 1])->get();
        $shippingcharge = ShippingCharge::where(['status' => 1, 'website' => 1])->get();
        $select_charge = ShippingCharge::where(['status' => 1, 'website' => 1])->first();
        $bkash_gateway = PaymentGateway::where(['status' => 1, 'type' => 'bkash'])->first();
        $shurjopay_gateway = PaymentGateway::where(['status' => 1, 'type' => 'shurjopay'])->first();
        $sms_gateway = SmsGateway::where(['status' => 1, 'order' => 1])->first();
        if (Session::get('free_shipping') == 1) {
            Session::put('shipping', 0);
        } else {
            Session::put('shipping', $select_charge->amount);
        }

        return view('frontEnd.layouts.customer.checkout', compact('shippingcharge', 'bkash_gateway', 'shurjopay_gateway', 'sms_gateway', 'paymentmethods'));
    }
    public function order_otp(Request $request)
    {
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where(['status' => 1])->first();
        if ($request->phone_number) {
            $url = "$sms_gateway->url";
            $otp = rand(1111, 9999);
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "number" => $request->phone_number,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "message" => "Dear Customer!\r\nYour order verify OTP is $otp \r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            Session::put('order_otp', $otp);
            return response()->json(['status' => 'success', 'message' => 'SMS Send successfully']);
        }
    }
    public function order_save(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required'
        ]);
        $sms_gateway = SmsGateway::where(['status' => 1, 'order' => 1])->first();
        if ($sms_gateway) {
            if (session::get('order_otp')) {
                $this->validate($request, [
                    'otp' => 'required',
                ]);
                if (session::get('order_otp') != $request->otp) {
                    Toastr::error('Your OTP not match', 'Failed!');
                    return redirect()->back();
                }
            }
        }
        // return $request->all();
        if (Cart::instance('shopping')->count() <= 0) {
            Toastr::error('Your shopping empty', 'Failed!');
            return redirect()->back();
        }

        $subtotal = Cart::instance('shopping')->subtotal();
        $subtotal = str_replace(',', '', $subtotal);
        $subtotal = str_replace('.00', '', $subtotal);
        $discount = Session::get('discount');

        $shippingfee = Session::get('free_shipping') ? 0 : Session::get('shipping');

        $shipping_area = ShippingCharge::where('id', $request->area)->first();
        if (Auth::guard('customer')->user()) {
            $customer_id = Auth::guard('customer')->user()->id;
        } else {
            $exits_customer = Customer::where('phone', $request->phone)->select('phone', 'id')->first();
            if ($exits_customer) {
                $customer_id = $exits_customer->id;
            } else {
                $password = rand(111111, 999999);
                $store = new Customer();
                $store->name = $request->name;
                $store->slug = $request->name;
                $store->phone = $request->phone;
                $store->password = bcrypt($password);
                $store->seller_request = 0;
                $store->verify = 1;
                $store->status = 'active';
                $store->save();
                $customer_id = $store->id;
            }
        }

        $reseller_id = Auth::guard('customer')->user() && Auth::guard('customer')->user()->seller_type == 1 ? Auth::guard('customer')->user()->reseller_id : $request->reseller_id;
        // return $reseller_id;
        if ($reseller_id) {
            $reseller_info = Customer::where(['reseller_id' => $reseller_id, 'status' => 'active'])->first();
        } else {
            $reseller_info = NULL;
        }
        $total_commision = 0;
        if ($reseller_info) {
            foreach (Cart::instance('shopping')->content() as $cart) {
                $total_commision += $cart->options->commision * $cart->qty;
            }
        }

        // order data save
        $order = new Order();
        $order->invoice_id = rand(11111, 99999);
        $order->amount = ($subtotal + $shippingfee) - $discount;
        $order->discount = $discount ? $discount : 0;
        $order->shipping_charge = $shippingfee;
        $order->customer_id = $customer_id;
        $order->reseller_id = $reseller_info != NuLL ? $reseller_info->id : NULL;
        $order->order_status = 1;
        $order->commision = $total_commision;
        $order->note = $request->note;
        //return $order;
        $order->save();

        //return $order;

        // shipping data save
        $shipping = new Shipping();
        $shipping->order_id = $order->id;
        $shipping->customer_id = $customer_id;
        $shipping->name = $request->name;
        $shipping->phone = $request->phone;
        $shipping->address = $request->address;
        $shipping->area = $shipping_area ? $shipping_area->name : 'Free Shipping';
        $shipping->save();

        // payment data save
        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->customer_id = $customer_id;
        $payment->payment_method = $request->payment_method;
        $payment->amount = $order->amount;
        $payment->trx_id = $request->trx_id ?? '';
        $payment->sender_number = $request->sender_number ?? '';
        $payment->payment_status = 'pending';
        $payment->save();

        // order details data save
        foreach (Cart::instance('shopping')->content() as $cart) {
            $order_details = new OrderDetails();
            $order_details->order_id = $order->id;
            $order_details->product_id = $cart->id;
            $order_details->product_name = $cart->name;
            $order_details->purchase_price = $cart->options->purchase_price;
            $order_details->product_color = $cart->options->product_color;
            $order_details->product_size = $cart->options->product_size;
            $order_details->type = $cart->options->type;
            $order_details->commision = $total_commision;
            $order_details->sale_price = $cart->price;
            $order_details->qty = $cart->qty;
            //return $order_details;
            $order_details->save();
        }
        Session::forget('free_shipping');
        Session::forget('order_otp');
        Cart::instance('shopping')->destroy();
        Session::put('purchase_event', 'true');

        Toastr::success('Thanks, Your order place successfully', 'Success!');
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where(['status' => 1])->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "number" => $request->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "message" => "Dear $request->name!\r\nYour order ($order->invoice_id) has been successfully placed. Track your order tracking https://projapotishop.com/customer/order-track and Total Bill $order->amount\r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        }

        if ($request->payment_method == 'bkash') {
            return redirect('/bkash/checkout-url/create?order_id=' . $order->id);
        } elseif ($request->payment_method == 'shurjopay') {
            $info = array(
                'currency' => "BDT",
                'amount' => $order->amount,
                'order_id' => uniqid(),
                'discsount_amount' => 0,
                'disc_percent' => 0,
                'client_ip' => $request->ip(),
                'customer_name' => $request->name,
                'customer_phone' => $request->phone,
                'email' => "customer@gmail.com",
                'customer_address' => $request->address,
                'customer_city' => $request->area,
                'customer_state' => $request->area,
                'customer_postcode' => "1212",
                'customer_country' => "BD",
                'value1' => $order->id
            );
            $shurjopay_service = new ShurjopayController();
            return $shurjopay_service->checkout($info);
        } else {
            return redirect('customer/order-success/' . $order->id);
        }

    }

    public function orders()
    {
        $orders = Order::where('customer_id', Auth::guard('customer')->user()->id)->with('status')->latest()->get();
        return view('frontEnd.layouts.customer.orders', compact('orders'));
    }
    public function order_success($id)
    {
        $order = Order::where('id', $id)->firstOrFail();
        return view('frontEnd.layouts.customer.order_success', compact('order'));
    }
    public function invoice(Request $request)
    {
        $order = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->with('orderdetails', 'payment', 'shipping', 'customer')->firstOrFail();
        return view('frontEnd.layouts.customer.invoice', compact('order'));
    }
    public function order_note(Request $request)
    {
        $order = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->firstOrFail();
        return view('frontEnd.layouts.customer.order_note', compact('order'));
    }
    public function profile_edit(Request $request)
    {
        $profile_edit = Customer::where(['id' => Auth::guard('customer')->user()->id])->firstOrFail();
        $districts = District::distinct()->select('district')->get();
        $areas = District::where(['district' => $profile_edit->district])->select('area_name', 'id')->get();
        return view('frontEnd.layouts.customer.profile_edit', compact('profile_edit', 'districts', 'areas'));
    }
    public function profile_update(Request $request)
    {
        $update_data = Customer::where(['id' => Auth::guard('customer')->user()->id])->firstOrFail();

        $image = $request->file('image');
        if ($image) {
            // image with intervention
            $name = time() . '-' . $image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name);
            $name = strtolower(Str::slug($name));
            $uploadpath = 'public/uploads/customer/';
            $imageUrl = $uploadpath . $name;
            $img = Image::make($image->getRealPath());
            $img->encode('webp', 90);
            $width = 120;
            $height = 120;
            $img->resize($width, $height);
            $img->save($imageUrl);
        } else {
            $imageUrl = $update_data->image;
        }

        $update_data->name = $request->name;
        $update_data->phone = $request->phone;
        $update_data->email = $request->email;
        $update_data->address = $request->address;
        $update_data->district = $request->district;
        $update_data->area = $request->area;
        $update_data->image = $imageUrl;
        $update_data->save();

        Toastr::success('Your profile update successfully', 'Success!');
        return redirect()->route('customer.account');
    }

    public function order_track()
    {
        return view('frontEnd.layouts.customer.order_track');
    }

    public function order_track_result(Request $request)
    {

        $phone = $request->phone;
        $invoice_id = $request->invoice_id;

        if ($phone != null && $invoice_id == null) {
            $order = DB::table('orders')
                ->join('shippings', 'orders.id', '=', 'shippings.order_id')
                ->where(['shippings.phone' => $request->phone])
                ->get();

        } else if ($invoice_id && $phone) {
            $order = DB::table('orders')
                ->join('shippings', 'orders.id', '=', 'shippings.order_id')
                ->where(['orders.invoice_id' => $request->invoice_id, 'shippings.phone' => $request->phone])
                ->get();
        }

        if ($order->count() == 0) {

            Toastr::error('message', 'Something Went Wrong !');
            return redirect()->back();
        }

        //   return $order->count();



        return view('frontEnd.layouts.customer.tracking_result', compact('order'));
    }


    public function change_pass()
    {
        return view('frontEnd.layouts.customer.change_password');
    }

    public function resellerGenerate()
    {
        $max_reseller = DB::table('customers')->max(DB::raw('CAST(reseller_id AS UNSIGNED)'));
        return $max_reseller ? $max_reseller + '1' : '100001';
    }

    public function password_update(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required_with:new_password|same:new_password|'
        ]);

        $customer = Customer::find(Auth::guard('customer')->user()->id);
        $hashPass = $customer->password;

        if (Hash::check($request->old_password, $hashPass)) {

            $customer->fill([
                'password' => Hash::make($request->new_password)
            ])->save();

            Toastr::success('Success', 'Password changed successfully!');
            return redirect()->route('customer.account');
        } else {
            Toastr::error('Failed', 'Old password not match!');
            return redirect()->back();
        }
    }
}
