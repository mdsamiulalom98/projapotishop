@extends('frontEnd.layouts.master')
@section('title','Reseller Pending')
@section('content')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center my-5">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-spinner display-5 text-danger"></i>
                        <h5 class="my-2 ">প্রিয় রিসেলার, আপনার একাউন্টি পর্যালোচনার জন্য রিভিতে রয়েছে, আমরা যত তাড়াতাড়ি সম্ভব এটি পর্যালোচনা করে সচল করে দিব, প্রজাপতি শপের সাথে থাকার জন্য ধন্যবাদ। </h5>
                        <a href="{{route('home')}}" class="btn btn-primary">Go Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script src="{{asset('public/frontEnd/')}}/js/parsley.min.js"></script>
<script src="{{asset('public/frontEnd/')}}/js/form-validation.init.js"></script>
@endpush