var currentPage = 1;
var isLoading = 0;
var windowHeight = $(document).height();

var renderItems = function(data){
    var compiled = _.template($("#xprs-menu-listing").html());
    html = compiled({
        items : data
    });

    $('#xprs_menu_container').empty().html(html);
};

loading_item_icon = $('.loading-items');

var renderScrollItems = function(data){
    var compiled = _.template($("#xprs-menu-listing").html());
    html = compiled({
        items : data
    });

    $('#xprs_menu_container').append(html);
};

function checkIfThereAreFilters() {
    clear_filter = $('.clear_filter_option');
    if($('.selected_filters li').length > 0){
        clear_filter.show();
    }else{
        clear_filter.hide();
    }
}
var getXprsItems = function(){

    checkIfThereAreFilters();

    form1 = $('#form-left');
    form2 = $('#form-right');
    loading_item_icon.addClass('show');
    isLoading = 0;
    fbq('track', 'Search');
    $.ajax({
        url : form1.attr('action'),
        type : 'GET',
        data : form1 .serialize() + '&' + form2.serialize()
    }).done(function(data){
        renderItems(data); 
        loading_item_icon.removeClass('show');
    });
};

var getXprsScrollItems = function(){

    checkIfThereAreFilters();

    form1 = $('#form-left');
    form2 = $('#form-right');
    loading_item_icon.addClass('show');
    isLoading = 1;
    $.ajax({
        url : form1.attr('action'),
        type : 'GET',
        data : form1 .serialize() + '&' + form2.serialize() + '&page=' + ( currentPage + 1)
    }).done(function(data){
        if(currentPage < data.pagination.last_page){
            isLoading = 0;
            currentPage = currentPage + 1;
            renderScrollItems(data);
        }
        
        loading_item_icon.removeClass('show');
    });
};



// checks for a element in another element
var checkIfFilterAlreadyExist = function(filter){

    alreadyExist = false;

    $('.selected_filters ul li').each(function(){
        current = $(this);
        if(current.html() == $(filter).html()){
            alreadyExist = true;
        }
    });

    return alreadyExist;
};


// xprs filter - category select2
$('.xprs_category_select2').on("select2:select", function (e) {
    e.preventDefault();

    select = $(this);
    value = select.val();
    text = select.find('option:selected').text();

    if(!value || !value.length)  return;

    html = '<li class="checkbox_holder">';
    html += '<label>';
    html += '<span class="txt">' + text + '</span>';
    html += '<input type="checkbox" name="category[]" class="inputcheckbox" checked value="' + value + '" />';
    html += '<span class="check_style"></span>';
    html += '</label>';
    html += '</li>';

    if(checkIfFilterAlreadyExist(html)) return;

    $('.selected_filters ul').append(html);
    currentPage = 1;
    getXprsItems();

    select.select2({
        minimumResultsForSearch: Infinity
    }).val("").trigger("change");
});

// xprs filter - cuisine select2
$('.xprs_cuisine_select2').on("select2:select", function (e) {
    e.preventDefault();
    select = $(this);

    value = select.val();
    text = select.find('option:selected').text();

    if(!value || !value.length)  return;

    html = '<li class="checkbox_holder">';
    html += '<label>';
    html += '<span class="txt">' + text + '</span>';
    html += '<input type="checkbox" name="cuisine[]" class="inputcheckbox" checked value="' + value + ' " />';
    html += '<span class="check_style"></span>';
    html += '</label>';
    html += '</li>';

    if(checkIfFilterAlreadyExist(html)) return;

    $('.selected_filters ul').append(html);
    currentPage = 1;
    getXprsItems();

    select.select2({
        minimumResultsForSearch: Infinity,
    }).val("").trigger("change");

});

// xprs menu filter - course select2
$('.xprs_course_select2').on("select2:select", function (e) {
    e.preventDefault();

    select = $(this);
    value = select.val();
    text = select.find('option:selected').text();

    if(!value || !value.length)  return;

    html = '<li class="checkbox_holder">';
    html += '<label>';
    html += '<span class="txt">' + text + '</span>';
    html += '<input type="checkbox" name="availability[]" class="inputcheckbox" checked value="' + value + '" />';
    html += '<span class="check_style"></span>';
    html += '</label>';
    html += '</li>';

    if(checkIfFilterAlreadyExist(html)) return;

    $('.selected_filters ul').append(html);
    currentPage = 1;
    getXprsItems();

    select.select2({
        minimumResultsForSearch: Infinity,
    }).val("").trigger("change");

});

// xprs menu filter - price select2
var rangeTimer;
$('.xprs_price_range').change(function(){
    clearTimeout(rangeTimer);

    rangeTimer = setTimeout(function(){
        currentPage = 1;
        getXprsItems();
    }, 400);
});

// xprs menu filter - type select2
$('.xprs_type_select2').on("select2:select", function (e) {
    e.preventDefault();
    currentPage = 1;
    getXprsItems();

});

// xprs menu filter - sort select2
$('.xprs_sort_select2').on("select2:select", function (e) {
    e.preventDefault();
    currentPage = 1;
    getXprsItems();
});


// search xprs menu by keyword
var typingTimer;
$('.xprs_search_by_keyword').keyup(function(e){
    clearTimeout(typingTimer);

    keyword = $(this);

    typingTimer = setTimeout(function(){
        currentPage = 1;
        getXprsItems();
    }, 400);
});

// remove filter on click
$(document).on('click', '.selected_filters ul li input[type="checkbox"]', function(){
    form = $(this).parents('form');
    filter = $(this).parents('.checkbox_holder');

    filter.remove();
    currentPage = 1;
    getXprsItems();

});

// remove all filters
$(document).on('click', '.clear_filters',function(e){
    e.preventDefault();
    clear_filter = $('.clear_filter_option');

    $('.selected_filters ul li').remove();

    // reset price range
    price_range = $('#priceRange');
    price_range.slider('setValue', [1, 1000]);
    currentPage = 1;
    getXprsItems();

    clear_filter.hide();
});

// homepage xprs menu select 2
$('.homepage_choose_select').change(function(e){
    form = $(this).parents('form');
    form.submit();
});

// fix sidebar filters

var fixSidebarFilters = function(){
  sidebar = $('.sidebar-filters');

    formOffsetTop = $('.xprs_menu_form').offset().top - 10;

    if($(document).scrollTop() > formOffsetTop){
       sidebar.addClass('fixed');
    }else{
        sidebar.removeClass('fixed');
    }
};

$(document).scroll(function(event) {

     if ( ( document.documentElement.clientHeight + $(document).scrollTop() ) >= ( document.body.offsetHeight - $('#buenoFooter').height()) && isLoading == 0){
        getXprsScrollItems();
     }

    if($('.xprs_menu_form').length){
        fixSidebarFilters();
    }

});