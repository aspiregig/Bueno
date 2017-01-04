
@extends('admin.master')

  @section('title')Bueno Kitchen @endsection

  @section('header')

  <!-- stylesheets -->
  @include('admin.partials.css')

  <!-- javascript -->
  @include('admin.partials.js')

  <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  @endsection

  @section('content')

  <!-- Content -->
    <div id="content">
      <div class="menubar">
                <div class="sidebar-toggler visible-xs">
                    <i class="ion-navicon"></i>
                </div>
                
        <div class="page-title">
          List of SEO titles
        </div>&nbsp;&nbsp;

        <a href="{{URL::route('admin.new_seo_title')}}" class="new-user btn btn-primary btn-xs">
          Add New</a>
      </div>

      <div class="content-wrapper">
        @include('admin.partials.flash')
        <table id="datatable-example">
                    <thead>
                        <tr>
                            <th tabindex="0" rowspan="1" colspan="1">Sno
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Alias Name
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Meta Title
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Meta Description
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Og Image
                            </th>
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <th rowspan="1" colspan="1">Sno</th>
                            <th rowspan="1" colspan="1">Alias Name</th>
                            <th rowspan="1" colspan="1">Meta Title</th>
                            <th rowspan="1" colspan="1">Meta Description</th>
                            <th rowspan="1" colspan="1">Og Image</th>


                        </tr>
                    </tfoot>
                    <tbody>
                    <?php $counter=1;?>
                      @foreach($pages as $web_page)
                        <tr>
                            <td>
                              {{$counter++}}
                            </td>
                            <td><a href="{{URL::route('admin.update_seo_title',$web_page->id)}}">{{$web_page->slug}}</a></td>
                            <td>{{$web_page->meta_title}}</td>
                            <td>{{$web_page->meta_description}}</td>
                            <td><img class="img-responsive product-img" src="{{route('photo.web_page',$web_page->meta_image_url)}}" width="100px"></td>
                        </tr>
                      @endforeach
                        
                                                                      
                    </tbody>
                </table>

      </div>

  @endsection

  @section('script')

   
  <script type="text/javascript">
        $(function() {
            $('#datatable-example').dataTable({
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10,
          "aLengthMenu": [[10, 50, 100, -1], [10, 50, 100, "All"]]
            });
        });
    </script>

  @endsection 