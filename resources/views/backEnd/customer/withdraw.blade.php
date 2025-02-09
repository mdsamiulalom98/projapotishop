@extends('backEnd.layouts.master')
@section('title',$title.' Withdraw')
@section('css')
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd/')}}/assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet" type="text/css" />
<style>
    p{
        margin:0;
    }
   @page { 
        margin: 50px 0px 0px 0px;
    }
   @media print {
    td{
        font-size: 18px;
    }
    p{
        margin:0;
    }
    title {
        font-size: 25px;
    }
    header,footer,.no-print,.left-side-menu,.navbar-custom {
      display: none !important;
    }
  }
</style>
@endsection 

@section('content')
<div class="container-fluid">
    
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title text-capitalize">{{$title}} Withdraw</h4>
            </div>
        </div>
    </div>   
    <form class="no-print">
        <div class="row">   
            <div class="col-sm-3">
                <div class="form-group mb-3">
                    <label for="seller_id" class="form-label">Customer </label>
                    <select class="form-control select2 @error('seller_id') is-invalid @enderror" name="seller_id" value="{{ old('seller_id') }}" >
                        <option value="">Select..</option>
                        @foreach($sellers as $value)
                        <option value="{{$value->id}}" @if(request()->get('seller_id') == $value->id) selected @endif>{{$value->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- col end -->
            <div class="col-sm-3">
                <div class="form-group">
                   <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" value="{{request()->get('start_date')}}"  class="form-control flatdate" name="start_date">
                </div>
            </div>
            <!--col-sm-3--> 
            <div class="col-sm-3">
                <div class="form-group">
                   <label for="end_date" class="form-label">End Date</label>
                    <input type="date" value="{{request()->get('end_date')}}" class="form-control flatdate" name="end_date">
                </div>
            </div>
            <!--col-sm-3-->
            <div class="col-sm-3">
                <div class="form-group mt-3">
                    <button class="btn btn-primary">Submit</button>
                </div>
            </div>
            <!-- col end -->
        </div>  
    </form>
    <!-- end page title -->
    <div class="row mb-3">
        <div class="col-sm-6 no-print">
             {{$withdraws->links('pagination::bootstrap-4')}}
        </div>
        <div class="col-sm-6">
            <div class="export-print text-end">
                <button onclick="printFunction()"class="no-print btn btn-success"><i class="fa fa-print"></i> Print</button>
                <button id="export-excel-button" class="no-print btn btn-info"><i class="fas fa-file-export"></i> Export</button>
            </div>
        </div>
    </div>
   <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="content-to-export" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th style="width:5%">SL</th>
                            <th style="width:17%">Request Date <br> Pay Date</th>
                            <th style="width:10%">Customer</th>
                            <th style="width:10%">Method</th>
                            <th style="width:10%">Receive</th>
                            <th style="width:10%">Amount</th>
                            <th style="width:10%">Note</th>
                            <th style="width:10%">Admin Note</th>
                            <th style="width:10%">Receipt</th>
                            <th style="width:8%">Status</th>
                            <th style="width:10%">Action</th>
                        </tr>
                    </thead>
                
                
                    <tbody>
                        @foreach($withdraws as $key=>$value)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{date('d-m-Y', strtotime($value->request_date))}},<br>{{date('d-m-Y', strtotime($value->pay_date))}}</td>
                            <td>{{$value->customer?$value->customer->name:''}}</td>
                            <td>{{$value->method}}</td>
                            <td>{{$value->receive}}</td>
                            <td>{{$value->amount}}</td>
                            <td>{{$value->note}}</td>
                            <td>{{$value->admin_note}}</td>
                            <td><a href="{{route('admin.slip',$value->id)}}" class="btn btn-xs btn-blue waves-effect waves-light"><i class="fe-eye"></i></a></td>
                            <td>{{$value->status}}</td>
                            <td>
                               <a data-bs-toggle="modal" data-bs-target="#withdraw{{$value->id}}" class="btn btn-success btn-xs btn-rounded">Status</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
 
            </div> <!-- end card body-->
        </div> <!-- end card -->
        <div class="custom-paginate">
            {{$withdraws->links('pagination::bootstrap-4')}}
        </div>
    </div><!-- end col-->
   </div>
</div>

@foreach($withdraws as $key=>$value)
<div class="modal fade" id="withdraw{{$value->id}}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
       <div class="modal-header">
        <h5 class="modal-title">Withdraw Status - {{$value->name}} ({{$loop->iteration}})</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
       <div class="modal-body">
           <form action="{{route('admin.withdraw_change')}}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{$value->id}}">
             <div class="form-group mb-3">
                 <label class="form-label">Status</label>
                 <select class="form-control" name="status" required>
                     <option value="">Select...</option>
                     <option  value="paid" {{$value->status == 'paid' ? 'selected':''}}>Paid</option>
                     <option  value="cancel" {{$value->status == 'cancel' ? 'selected':''}}>cancel</option>
                 </select>
             </div>
             <div class="form-group mb-3">
                 <label class="form-label">Admin Note</label>
                 <textarea name="admin_note" class="form-control" required>{{$value->admin_note}}</textarea>
             </div>
             <div class="form-group">
                 <button {{$value->status=='paid'?'disabled':''}} type="submit" class="btn btn-success change-confirm">Submit</button>
             </div>
            </form>
       </div>
    </div>
  </div>
</div>
@endforeach
@endsection

@section('script')
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/flatpickr/flatpickr.min.js"></script>
<script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2();
        flatpickr(".flatdate", {});
    });
</script>
<script>
    function printFunction() {
        window.print();
    }
</script>
<script>
    $(document).ready(function() {
        $('#export-excel-button').on('click', function() {
            var contentToExport = $('#content-to-export').html();
            var tempElement = $('<div>');
            tempElement.html(contentToExport);
            tempElement.find('.table').table2excel({
                exclude: ".no-export",
                name: "Order Report" 
            });
        });
    });
</script>
@endsection