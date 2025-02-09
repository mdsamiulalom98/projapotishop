@extends('frontEnd.layouts.master')
@section('title','Customer Withdraw')
@section('content')
<section class="customer-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="customer-sidebar">
                    @include('frontEnd.layouts.customer.sidebar')
                </div>
            </div>
            <div class="col-sm-9">
                <div class="customer-content">
                    <h5 class="account-title">Withdraw</h5>
                    <div class="my-dashboard">
                        <button class="withdraw_rquest btn btn-success" data-bs-toggle="modal" data-bs-target="#withdraw">New Withdraw</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Request Date</th>
                                    <th>Payment</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($withdraws as $key=>$value)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                     <td>{{date('d-m-Y', strtotime($value->request_date))}} ,{{date('h:i a', strtotime($value->request_date))}}</td>
                                     <td>{{$value->method}}</td>
                                    <td>{{$value->amount}}</td>
                                     <td class="text-capitalize">{{$value->status}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Modal -->
<div class="modal fade" id="withdraw" tabindex="-1" aria-labelledby="withdraw" aria-hidden="true">
  <div class="modal-dialog custom_modal">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="withdrawLabel">New Withdraw</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('customer.withdraw_request')}}" method="POST" class="withdraw_form">
             @csrf
             <div class="from-group">
                <label for="amount">Amount *<strong class="text-danger"> (Current Balance {{Auth::guard('customer')->user()->balance}} TK)</strong></label>
                 <input type="number" id="amount" name="amount" class="form-control border" placeholder="Enter Amount" required>
             </div>
             <div class="from-group">
                <label for="method">Payment Method *</label>
                 <select name="method" id="method" class="form-control border" required>
                     <option value="">Select..</option>
                     <option value="bKash">bKash</option>
                     <option value="Nagad">Nagad</option>
                     <option value="Rocket">Rocket</option>
                     <option value="Bank">Bank</option>
                 </select>
             </div>
             <div class="from-group">
                <label for="amount">Receive Number *</label>
                 <input type="number" id="amount" name="receive" class="form-control border" placeholder="Enter Receive Number" required>
             </div>
             <div class="from-group">
                <label for="note">Note</label>
                 <textarea  id="note" name="note" class="form-control border" placeholder="Write your payment receive information"></textarea>
             </div>
             <div class="from-group">
                <label for="password"> Your Password *</label>
                 <input type="password" id="password" name="password" class="form-control border" placeholder="Enter Your Password" required>
             </div>
             <div clas
             <div class="form-group my-2">
                 <button type="submit" class="btn btn-success"> Submit Withdraw</button>
             </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection