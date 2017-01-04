    $(function () {


       Messenger.options = {
                extraClasses: 'messenger-fixed messenger-on-bottom messenger-on-right',
                theme: 'flat'
            }

      $('input.hot-deal').on('change', function() {
        if ($('input.hot-deal:checked').length>0) {
          if($('input.not-sale:checked').length>0)
          {
            Messenger().post({
                  message: 'Meal Cannot be Hot Deal and Not For Sale at same time',
                  type: 'error',
                  showCloseButton: true
              });
          $('input.not-sale').not(this).prop('checked', false);
        }
        }
      });

        $('input.xprs-menu').on('change', function() {
        if ($('input.xprs-menu:checked').length>0) {
          if($('input.not-sale:checked').length>0)
          {
            Messenger().post({
                  message: 'Meal Cannot be in Xprs Menu and Not For Sale at same time',
                  type: 'error',
                  showCloseButton: true
              });
          $('input.not-sale').not(this).prop('checked', false);
        }
      }
        });

      $('input.not-sale').on('change', function() {
        if ($('input.not-sale:checked').length>0) {
          
          if($('input.xprs-menu:checked').length>0 || $('input.hot-deal:checked').length>0)
          {
            Messenger().post({
                  message: 'Meal Cannot be in Xprs Menu / Hot Meal and Not For Sale at same time',
                  type: 'error',
                  showCloseButton: true
              });
          $('input.hot-deal').not(this).prop('checked', false);
          $('input.xprs-menu').not(this).prop('checked', false);
          }
        }
        });
     

    });
function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'')
        ;
}
$('#availability').select2();

$('.item-name').keyup(function(event) {
  var text = $(this).val();
  var slug = convertToSlug(text);
  $('.slug').val(slug);
});