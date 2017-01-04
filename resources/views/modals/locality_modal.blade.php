<div id="localityModal" class="black_modal modal fade locality_select_modal"  data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <div class="header_icon icon_circle">
                    <i class="ion-alert"></i>
                </div> <!-- header_icon ends -->
                <h4 class="modal-title">You need to select your locality<br/> to be able to browse the site.</h4>
                <hr class="white no-marginbottom" />
            </div> <!-- modal-header ends -->
            <div class="modal-body text-center">
                <form action="{{ route('users.area.post') }}" method="POST">
                    {{ csrf_field() }}
                <h4 class="no-margintop marginbottom-md">Please select your locality for delivery in Gurgaon</h4>
                <label for="" class="bueno_select no_caret">
                    <select name="area_id" id="" class="full_width locality_select2">
                        <option value="">Select locality in Gurgaon</option>
                    </select>
                </label>
                </form>
            </div> <!-- modal-body ends -->
        </div> <!-- modal-content ends -->
    </div> <!-- modal-dialog ends -->
</div> <!-- modal ends -->