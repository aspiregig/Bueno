@extends('layouts.master')

@section('content')
    @include('partials/static_links_nav')

    <section class="static_page_sec press_sec">

        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    <div class="well padding-md">
                        <h3 class="normal_header">In the Press</h3>
                    </div> <!-- well ends -->

                    <div class="static_content col-xs-12">
                        <div class="main col-xs-12 col-md-9">
                            @foreach($press->chunk(2) as $chunk)
                                <div class="row">
                                    @foreach($chunk as $single_press)
                                        <div class="open_card col-xs-12 col-sm-6">
                                            <div class="image">
                                                <a href = "{{ $single_press->url }}">
                                                    <span class="item-image" style="background-image:url('{{ $single_press->image_url }}')"></span>
                                                </a>
                                            </div> <!-- image ends -->
                                            <div class="body">
                                                <h4>{{ $single_press->title }}</h4>
                                                <p><strong>{{ $single_press->source_name }}</strong> | {{ $single_press->date }}</p>
                                            </div> <!-- body ends -->
                                        </div> <!-- open_card ends -->
                                    @endforeach
                                </div> <!-- row ends -->

                            @endforeach
                        </div> <!-- main ends -->
                    </div> <!-- static_content ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- <section> ends -->

@stop
