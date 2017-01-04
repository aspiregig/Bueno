
@extends('layouts.master')

@section('content')
    <!-- ############################## -->
    <!-- ############ BODY ############ -->
    <!-- ############################## -->

    @include('partials/static_links_nav')

    <section class="static_page_sec privacy_sec">

        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    <div class="well padding-md">
                        <h3 class="normal_header">Refunds &amp; Cancellation</h3>
                    </div> <!-- well ends -->

                    <div class="static_content col-xs-12">
                        <div class="main col-xs-12 col-md-9">
                            <p>You must notify Bueno immediately if you decide to cancel your order, preferably by phone, and quote your order number. If Bueno accepts your cancellation, no cancellation fee applies. If the restaurant refuses cancellation, e.g. because preparation of Food Delivery has been completed and/or delivery personnel has already been dispatched, it may not be cancelled. We will not be able to refund any order, which has been already dispatched.</p>

                            <hr class="row dotted">

                            <p>We may cancel a contract if the product is not available for any reason. We will notify you if this is the case and return any payment that you have made.</p>

                            <hr class="row dotted">

                            <p>If the cancellation was made in time and once the restaurant has accepted your cancellation, we will refund or re-credit your debit or credit card with the full amount within 14 days, which includes the initial delivery charge (where applicable) which you paid for the delivery of the Goods or the Services, as applicable.</p>

                            <hr class="row dotted">

                            <p>In the unlikely event that Bueno delivers a wrong item, you have the right to reject the delivery of the wrong item and you shall be fully refunded for the missing item. If Bueno can only do a partial delivery (a few items might be not available), its staff should inform you or propose a replacement for missing items. You have the right to refuse a partial order before delivery and get a refund. The issue has to be settled directly with Bueno.</p>
                        </div> <!-- main ends -->
                    </div> <!-- static_content ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- <section> ends -->
@stop