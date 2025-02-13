@extends('backEnd.layouts.master')
@section('title','Campaign Sort')
@section('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
@endsection

@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('campaign.index')}}" class="btn btn-primary rounded-pill">Campaign Manage</a>
                </div>
                <h4 class="page-title">Campaign Sort</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
   <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
               <ul id="category-list">
                   @foreach($data as $key=>$value)
                   <li class=" d-block mb-2"  data-id="{{ $value->id }}">
                        <span style="min-width: 20px" class="d-inline-block">
                            {{ $loop->iteration }}
                        </span><span style="min-width: 180px"
                            class="btn btn-success ">{{ $value->name }}</span>
                    </li>
                   @endforeach
                </ul>
                <button id="save-order" class="btn btn-success">Save Order</button>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
   </div>
</div>
@endsection


@section('script')

 <script>
        // Initialize Sortable
        var sortable = new Sortable(document.getElementById('category-list'), {
            animation: 150
        });

        // Save the order to the server
        document.getElementById('save-order').addEventListener('click', function() {
            var order = sortable.toArray();

            fetch('{{ route('campaign.orderupdate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order: order
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Order saved successfully!');
                    } else {
                        alert('Failed to save order.');
                    }
                });
        });
    </script>
<!-- third party js ends -->
@endsection
