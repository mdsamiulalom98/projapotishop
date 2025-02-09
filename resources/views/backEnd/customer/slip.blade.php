@extends('backEnd.layouts.master')
@section('title','Withdraw Slip')
@section('content')
<style>
    .customer-invoice {
        margin: 25px 0;
    }
    .invoice_btn{
        margin-bottom: 15px;
    }
    p{
        margin:0;
    }
    td{
        font-size: 16px;
    }
   @page { 
    margin:0px;
    }
   @media print {
    .invoice-innter{
        margin-left: -120px !important;
    }
    .invoice_btn{
        margin-bottom: 0 !important;
    }
    td{
        font-size: 18px;
    }
    p{
        margin:0;
    }
    header,footer,.no-print,.left-side-menu,.navbar-custom {
      display: none !important;
    }
  }
</style>
<section class="customer-invoice ">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <a href="{{ route('admin.withdraw',$slip_data->status) }}" class="no-print"><strong><i class="fe-arrow-left"></i> Back To Withdraw</strong></a>
            </div>
            <div class="col-sm-6">
                <button onclick="printFunction()"class="no-print btn btn-xs btn-success waves-effect waves-light"><i class="fa fa-print"></i></button>
            </div>
            <div class="col-sm-12 mt-3">
                <div class="invoice-innter" style="width:384px;margin: 0 auto;background: #fff;overflow: hidden;padding: 30px;padding-top: 0;">
                    <table style="width:100%">
                        <colgroup>
                            <col  >
                            <col >
                          </colgroup>
                        <tr style="border-bottom: 1px dashed">
                            <td style="padding-top: 15px;">
                                <img src="{{asset($generalsetting->white_logo)}}" style="margin-top:25px !important;display: block;margin: 0 auto;margin-bottom: 15px;width: 180px" alt="">
                                <div class="invoice_form" style="text-align: center;padding-bottom: 15px">
                                    <p style="font-size:16px;line-height:1.8;color:#222">{{$generalsetting->name}}</p>
                                    <p style="font-size:16px;line-height:1.8;color:#222">{{$contact->phone}}</p>
                                    <p style="font-size:16px;line-height:1.8;color:#222">{{$contact->email}}</p>
                                    <p style="font-size:16px;line-height:1.8;color:#222">{{$contact->address}}</p>
                                </div>
                            </td>
                        </tr>
                    </table style="width:100%">
                    <table style="width:100%">
                        <tr>
                            
                            <td  style="">
                                <div class="invoice_to" style="text-align: left; padding-top: 15px; padding-bottom: 15px;">
                                    
                                    <p style="font-size:16px;line-height:1.8;color:#222;text-transform:capitalize"><strong>Status :</strong> {{$slip_data->status}}</p>
                                    <p style="font-size:16px;line-height:1.8;color:#222;"><strong>Name :</strong> {{$slip_data->customer->name ?? ''}}</p>
                                    <p style="font-size:16px;line-height:1.8;color:#222;"><strong>Sender Number :</strong> {{$slip_data->receive}}</p>
                                    <p style="font-size:16px;line-height:1.8;color:#222;"><strong>Payment Method :</strong> {{$slip_data->method}}</p>
                                    <!--<p style="font-size:16px;line-height:1.8;color:#222;"><strong>Transaction NO :</strong> {{$slip_data->transaction}}</p>-->
                                    <p style="font-size:16px;line-height:1.8;color:#222;"><strong>Withdraw Amount :</strong> {{$slip_data->amount}}</p>
                                    <p style="font-size:16px;line-height:1.8;color:#222;"><strong>Confirmation Time :</strong> {{$slip_data->updated_at}}</p>
                                   
                                </div>
                            </td>
                        </tr>
                    </table>
                    
                    
                    
                    
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function printFunction() {
        window.print();
    }
</script>
@endsection
