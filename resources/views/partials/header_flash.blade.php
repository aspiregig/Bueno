@if(Session::has('flash_notification.message'))
    <section class="title_sec gray-dim-bg header_flash">
        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    <div class="main-sec">
                        {!!  Session::get('flash_notification.message') !!}
                    </div> <!-- main-sec ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->
    </section>
@endif