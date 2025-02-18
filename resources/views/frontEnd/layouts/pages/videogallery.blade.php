@extends('frontEnd.layouts.master')
@section('title', 'Page')
@section('content')
    <section class="page_breadcumb">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page_title">
                        <h5>Video Gallery</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="video-gallery-section ">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="gallery-inner">
                        @foreach ($videogalleries as $key => $value)
                        <div class="gallery-item">
                            <iframe  height="480" src="https://www.youtube.com/embed/{{ $value->link }}"  frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
