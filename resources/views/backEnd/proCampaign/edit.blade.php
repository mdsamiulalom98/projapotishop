@extends('backEnd.layouts.master')
@section('title','Campaign Edit')
@section('css')
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd')}}/assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">
    
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('proCampaign.index')}}" class="btn btn-primary rounded-pill">Manage</a>
                </div>
                <h4 class="page-title">Campaign Edit</h4>
            </div>
        </div>
    </div>       
    <!-- end page title --> 
   <div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <form action="{{route('proCampaign.update')}}" method="POST" class=row data-parsley-validate=""  enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{$edit_data->id}}" name="hidden_id">
                    <div class="col-sm-4">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $edit_data->name}}"  id="name" required="">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col-end -->
                    <div class="col-sm-4">
                        <div class="form-group mb-3">
                            <label for="titlecolor" class="form-label">Title Color *</label>
                            <input type="text" class="form-control @error('titlecolor') is-invalid @enderror" name="titlecolor" value="{{ $edit_data->titlecolor}}"  id="titlecolor" required="">
                            @error('titlecolor')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col-end -->
                    <div class="col-sm-4">
                        <div class="form-group mb-3">
                            <label for="bgcolor" class="form-label">Background Color *</label>
                            <input type="text" class="form-control @error('bgcolor') is-invalid @enderror" name="bgcolor" value="{{ $edit_data->bgcolor}}"  id="bgcolor" required="">
                            @error('bgcolor')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col-end -->
                      <div class="col-sm-6 mb-3">
                            <div class="form-group">
                                <label for="image" class="form-label">Image *</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    name="image" value="{{ $edit_data->image }}" id="image">
                                <img src="{{ asset($edit_data->image) }}" alt="" class="edit-image">
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col end -->
                        <div class="col-sm-6 mb-3">
                            <div class="form-group">
                                <label for="image2" class="form-label">Banner Image *</label>
                                <input type="file" class="form-control @error('image2') is-invalid @enderror"
                                    name="image2" value="{{ $edit_data->image2 }}" id="image2">
                                <img src="{{ asset($edit_data->image2) }}" alt="" class="edit-image">
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col end -->
                    <div class="col-sm-6">
                        <div class="form-group mb-3">
                            <label for="date" class="form-label">Date  *</label>
                            <input type="datetime-local" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ $edit_data->date}}"  id="date" required="">
                            @error('date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col-end -->
                    <div class="col-sm-6">
                        <div class="form-group mb-3">
                            <label for="discount" class="form-label">Discount  *</label>
                            <input type="number" class="form-control @error('discount') is-invalid @enderror" name="discount" value="{{ $edit_data->discount}}"  id="discount" required="">
                            @error('discount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col-end -->
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="product_id" class="form-label">Products *</label>
                             <select class="form-control select2-multiple @error('product_ids[]') is-invalid @enderror" value="{{ old('product_ids[]') }}" name="product_ids[]" data-toggle="select2" multiple  data-placeholder="Choose ...">
                                <optgroup >
                                    <option value="">Select..</option>
                                    @foreach($products as $value)
                                    <option value="{{$value->id}}" @foreach($select_products as $select_product) {{$select_product->id == $value->id?'selected':''}} @endforeach>{{$value->name}}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                            @error('product_ids[]')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-sm-3 mb-3">
                        <div class="form-group">
                            <label for="status" class="d-block">Status</label>
                            <label class="switch">
                              <input type="checkbox" value="1" name="status" @if($edit_data->status==1)checked @endif>
                              <span class="slider round"></span>
                            </label>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <div class="form-group">
                            <label for="front_view" class="d-block">Front View</label>
                            <label class="switch">
                              <input type="checkbox" value="1" name="front_view" @if($edit_data->front_view==1)checked @endif>
                              <span class="slider round"></span>
                            </label>
                            @error('front_view')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col end -->
                    <div>
                        <input type="submit" class="btn btn-success" value="Submit">
                    </div>

                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
   </div>
</div>
@endsection

@section('script')
<script src="{{asset('public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/flatpickr/flatpickr.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-pickers.init.js"></script>
@endsection