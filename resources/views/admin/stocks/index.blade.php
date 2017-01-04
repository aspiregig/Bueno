
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
                List of Meal Stocks
            </div>&nbsp;&nbsp;

        </div>

        <div class="content-wrapper">

            @include('admin.partials.flash')

            <table id="datatable-example">
                <thead>
                <tr>
                    <th tabindex="0" rowspan="1" colspan="1">Items
                    </th>
                    @foreach($kitchens as $kitchen)
                    <th tabindex="0" rowspan="1" colspan="1">{{$kitchen->name}}
                    </th>
                    @endforeach
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th rowspan="1" colspan="1">Sno</th>
                    @foreach($kitchens as $kitchen)
                        <th rowspan="1" colspan="1">{{$kitchen->name}}</th>
                    @endforeach
                </tr>
                </tfoot>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>
                            {{ $item->itemable->name}}
                        </td>
                        @foreach($kitchens as $kitchen)
                            <td rowspan="1" colspan="1">
                                <a href="{{ route('admin.stocks.edit',$item->getStockByKitchenId($kitchen->id)['id'])}}">{{$item->getStockByKitchenId($kitchen->id)['value']}}</a>
                            </td>
                        @endforeach
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