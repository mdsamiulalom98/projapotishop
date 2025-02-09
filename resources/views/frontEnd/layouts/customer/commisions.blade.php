@extends('frontEnd.layouts.master')
@section('title','My Commisions')
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
                    <h5 class="account-title">My Commisions</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Invoice</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($commissions as $key=>$value)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$value->customer?$value->customer->name:''}}</td>
                                    <td>{{$value->created_at->format('d-m-y')}}</td>
                                    <td>{{$value->invoice_id}}</td>
                                    <td>৳{{$value->commision}}</td>
                                </tr>
                                @endforeach
                                 <tr>
                                    <td colspan="4" class="text-right"><strong>Total Commission:</strong></td>
                                    <td><strong>৳{{ $totalCommission }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="custom-paginate">
                    {{$commissions->links('pagination::bootstrap-4')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection