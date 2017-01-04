<div id="localityModal" class="black_modal modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header text-center">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="ion-ios-close-empty"></i></span></button>
        <div class="header_icon icon_circle">
          <i class="ion-alert"></i>
        </div> <!-- header_icon ends -->
        <h4 class="modal-title">You need to select your locality<br/>to be able to add items to your cart.</h4>
        <hr class="no-marginbottom" />
      </div> <!-- modal-header ends -->
      <div class="modal-body text-center">
        <h4 class="no-margintop marginbottom-md">Please Select your locality for delivery</h4>
        <label for="" class="bueno_select no_caret">
          <select name="" id="" class="full_width bueno_select2">
            <option value="default">Select locality</option>
            <?php for ($i = 0; $i <= 6; $i++) { ?>
              <option value="<?php echo $i ?>"><?php echo $i ?></option>
            <?php } ?>
          </select>
        </label>
      </div> <!-- modal-body ends -->
    </div> <!-- modal-content ends -->
  </div> <!-- modal-dialog ends -->
</div> <!-- modal ends -->