$('.start-date').datetimepicker({
    autoclose: true,
    orientation: 'right top',
    startDate: '-5y',
    endDate: new Date()
});

$('.end-date').datetimepicker({
    autoclose: true,
    orientation: 'right top',
    startDate: '-5y',
    endDate: new Date()
});

if ($('#content').height < $('#sidebar-default')) {
    $('#sidebar-default').addClass('extra_height');
} else {
    $('#sidebar-default').addClass('normal_height');
}



$('#sidebar-default .menu-section ul li a').on('click', function() {
    console.log('new code');
    if ($(this).hasClass('toggled')) {
        if ($('#sidebar-default').hasClass('normal_height')) {
            $('#sidebar-default').removeClass('normal_height');
            $('#sidebar-default').addClass('extra_height');
        } else {
            $('#sidebar-default').removeClass('extra_height');
            $('#sidebar-default').addClass('normal_height');
        }
    } else {
        if ($('#sidebar-default').hasClass('normal_height')) {
            $('#sidebar-default').removeClass('normal_height');
            $('#sidebar-default').addClass('extra_height');
        } else if ($('#sidebar-default').hasClass('extra_height')) {
            $('#sidebar-default').removeClass('normal_height');
        } else {
            $('#sidebar-default').removeClass('extra_height');
            $('#sidebar-default').addClass('normal_height');
        }
    }
});
