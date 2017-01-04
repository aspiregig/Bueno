@extends('admin.master')

  @section('title') Bueno Kitchen @endsection

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

  <div id="content">
      <div class="menubar">
        <div class="sidebar-toggler visible-xs">
          <i class="ion-navicon"></i>
        </div>

        <div class="page-title">
          Update Coupon
        </div>
      </div>

      <div class="content-wrapper">
        <form id="new-product" class="form-horizontal" method="post" action="#" role="form">
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Coupon Code</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="product[first_name]" value="TT20" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Generate Number of Coupon Code</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="product[first_name]" placeholder="50" />
              </div>
            </div>
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Coupon Type</label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control" data-smart-select>
                  <option>Discount</option>
                  <option>Amount</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Discount Percentage</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="product[last_name]"  value="20" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Minimum Order Amount</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="product[last_name]" value="300" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Discount on Quantity(Minimum)</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="product[last_name]" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Maximum Discount Amount </label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="product[first_name]" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Coupon Value</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="product[first_name]" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Cashback Value</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="product[first_name]" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Loyality Points</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="product[first_name]" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Company/Society/Building Name</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="product[first_name]" />
              </div>
            </div>
            <div class="form-group">
            <label class="col-sm-2 col-md-2 control-label">Category</label>
                <div class="col-sm-10 col-md-8">
                    <div class="has-feedback">
                        <select name="data[]" multiple="multiple" class="GroupGroup form-control">
                          <option>Burgers, Wraps & Sandwiches</option>
                          <option>Durum Wheat Pasta</option>
                          <option>Biryanis & Curries</option>
                          <option>Global Rice Meals</option>
                          <option>Snacks & Desserts</option>
                          </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Check Cart</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="well">
                                
                                
                                <div class="control-group">
                                    <div class="input select"><label for="OrderMealId1">Meal 1</label>
                                        <select name="data[Order][meal_id1]" class="meals_dp" id="OrderMealId1">
                                                <option value="3-160.00">Smoked Vegetable Sandwich</option>
                                                <option value="4-170.00">Pita Bread with Paprika Hummus</option>
                                                <option value="5-170.00">Nachos with Fresh Tomato &amp; Cilantro Salsa</option>
                                                <option value="6-230.00">Mexican Rice Veg with Paprika Yoghurt</option>
                                                <option value="7-250.00">Mexican Rice Chicken with Paprika Yoghurt</option>
                                                <option value="8-160.00">Cucumber, Tomato and Cheese Sandwich</option>
                                                <option value="9-180.00">Chicken Ham &amp; Cheese Sandwich</option>
                                                <option value="10-220.00">Bueno Special Vegetable Biryani with Raita</option>
                                                <option value="11-180.00">Chicken Seekh Wrap</option>
                                                <option value="12-190.00">Mutton Seekh Wrap</option>
                                                <option value="13-240.00"> Bueno Special Chicken Biryani with Raita</option>
                                                <option value="14-250.00" selected="selected">Chinese Greens in Hot Garlic Sauce with Fried Rice</option>
                                                <option value="15-270.00">Thai Green Curry with Steamed Rice</option>
                                                <option value="16-270.00">Shredded Chicken in Hot Garlic Sauce with Veg Fried Rice</option>
                                                <option value="17-290.00">Thai Red Curry Chicken with Steamed Rice</option>
                                                <option value="18-230.00">Penne Primavera Veg</option>
                                                <option value="19-230.00">Spaghetti Aglio e Olio Veg</option>
                                                <option value="20-230.00">Penne Basil Pesto Veg</option>
                                                <option value="21-230.00">Penne Alfredo Veg</option>
                                                <option value="22-230.00">Penne Arrabiata Veg</option>
                                                <option value="23-250.00">Penne Primavera Chicken</option>
                                                <option value="24-250.00">Spaghetti Aglio e Olio Chicken</option>
                                                <option value="25-250.00">Penne Basil Pesto Chicken</option>
                                                <option value="26-250.00">Penne Alfredo Chicken</option>
                                                <option value="27-250.00">Penne Arrabiata Chicken</option>
                                                <option value="28-150.00">Walnut Brownie</option>
                                                <option value="29-180.00">Chicken Seekh Burger</option>
                                                <option value="30-190.00">Mutton Seekh Burger</option>
                                                <option value="31-170.00">Mexican Bean Burger Veg</option>
                                                <option value="32-160.00">Shami Burger Veg</option>
                                                <option value="33-240.00">Paneer Tawa Masala with Roomali Roti</option>
                                                <option value="34-260.00">Mutton Seekh Tawa Masala with Roomali Roti</option>
                                                <option value="35-240.00">Chicken Seekh Tawa Masala with Roomali Roti</option>
                                                <option value="36-220.00">Aloo Tawa Masala With Roomali Roti</option>
                                                <option value="37-170.00">Spicy Bean Wrap Veg</option>
                                                <option value="42-160.00">Shami Wrap Veg</option>
                                        </select>
                                        <label for="OrderQuantity1">Quantity 1</label><input name="data[Order][quantity1]" class="qty_dp" value="1" type="text" id="OrderQuantity1" /><br/><label class="col-sm-2 col-md-2 control-label"></label><a href="#" class="btn btn-danger btn-xs">add a meal</a> 
                          
                                    </div>

                          
                                 </div>
                                </div>
                                
                                
                            </div>
                        </div>
            <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Give away Inhouse Product</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="well">
                                
                                
                                <div class="control-group">
                                    <div class="input select"><label for="OrderMealId1">Meal 1</label>
                                        <select name="data[Order][meal_id1]" class="meals_dp" id="OrderMealId1">
                                                <option value="3-160.00">Smoked Vegetable Sandwich</option>
                                                <option value="4-170.00">Pita Bread with Paprika Hummus</option>
                                                <option value="5-170.00">Nachos with Fresh Tomato &amp; Cilantro Salsa</option>
                                                <option value="6-230.00">Mexican Rice Veg with Paprika Yoghurt</option>
                                                <option value="7-250.00">Mexican Rice Chicken with Paprika Yoghurt</option>
                                                <option value="8-160.00">Cucumber, Tomato and Cheese Sandwich</option>
                                                <option value="9-180.00">Chicken Ham &amp; Cheese Sandwich</option>
                                                <option value="10-220.00">Bueno Special Vegetable Biryani with Raita</option>
                                                <option value="11-180.00">Chicken Seekh Wrap</option>
                                                <option value="12-190.00">Mutton Seekh Wrap</option>
                                                <option value="13-240.00"> Bueno Special Chicken Biryani with Raita</option>
                                                <option value="14-250.00" selected="selected">Chinese Greens in Hot Garlic Sauce with Fried Rice</option>
                                                <option value="15-270.00">Thai Green Curry with Steamed Rice</option>
                                                <option value="16-270.00">Shredded Chicken in Hot Garlic Sauce with Veg Fried Rice</option>
                                                <option value="17-290.00">Thai Red Curry Chicken with Steamed Rice</option>
                                                <option value="18-230.00">Penne Primavera Veg</option>
                                                <option value="19-230.00">Spaghetti Aglio e Olio Veg</option>
                                                <option value="20-230.00">Penne Basil Pesto Veg</option>
                                                <option value="21-230.00">Penne Alfredo Veg</option>
                                                <option value="22-230.00">Penne Arrabiata Veg</option>
                                                <option value="23-250.00">Penne Primavera Chicken</option>
                                                <option value="24-250.00">Spaghetti Aglio e Olio Chicken</option>
                                                <option value="25-250.00">Penne Basil Pesto Chicken</option>
                                                <option value="26-250.00">Penne Alfredo Chicken</option>
                                                <option value="27-250.00">Penne Arrabiata Chicken</option>
                                                <option value="28-150.00">Walnut Brownie</option>
                                                <option value="29-180.00">Chicken Seekh Burger</option>
                                                <option value="30-190.00">Mutton Seekh Burger</option>
                                                <option value="31-170.00">Mexican Bean Burger Veg</option>
                                                <option value="32-160.00">Shami Burger Veg</option>
                                                <option value="33-240.00">Paneer Tawa Masala with Roomali Roti</option>
                                                <option value="34-260.00">Mutton Seekh Tawa Masala with Roomali Roti</option>
                                                <option value="35-240.00">Chicken Seekh Tawa Masala with Roomali Roti</option>
                                                <option value="36-220.00">Aloo Tawa Masala With Roomali Roti</option>
                                                <option value="37-170.00">Spicy Bean Wrap Veg</option>
                                                <option value="42-160.00">Shami Wrap Veg</option>
                                        </select>
                                        <label for="OrderQuantity1">Quantity 1</label><input name="data[Order][quantity1]" class="qty_dp" value="1" type="text" id="OrderQuantity1" /><br/><label class="col-sm-2 col-md-2 control-label"></label><a href="#" class="btn btn-danger btn-xs">add a meal</a> 
                          
                                    </div>

                          
                                 </div>
                                </div>
                                
                                
                            </div>
                        </div>
            <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Coupon Apply with</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="data[]" multiple="multiple" class="GroupGroup form-control">
                                <option value="1513">Brownie</option>
                                <option value="1705">Burger</option>
                                <option value="391">Wrap</option>
                                <option value="1966">Sandwich</option>
                                <option value="1513">Nachos </option>
                                <option value="1705">Pasta</option>
                                </select>
                            </div>
                        </div>
            </div>
            <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Cuisine</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="data[Group][Group][]" multiple="multiple" class="GroupGroup form-control">
                                <option value="1513">Mexican</option>
                                <option value="1705">Indian</option>
                                <option value="391">Chinese</option>
                                </select>
                            </div>
                        </div>
            </div> 
            <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Other Goodies</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="data[Group][Group][]" multiple="multiple" class="GroupGroup form-control">
                                <option value="1513">Movie Ticket</option>
                                <option value="1705">Mug</option>
                                </select>
                            </div>
                        </div>
            </div> 
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Description</label>
              <div class="col-sm-10 col-md-8">
                <div id="summernote"></div>
              </div>
            </div>
            <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Day</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="data[Group][Group][]" multiple="multiple" class="GroupGroup form-control">
                                <option value="1513">Sunday</option>
                                <option value="1705">Monday</option>
                                <option value="391">Tuesday</option>
                                <option value="1966">Wednesday</option>
                                <option value="1513">Thrusday</option>
                                <option value="1705">Friday</option>
                                <option value="391">Saturday</option>
                                </select>
                            </div>
                        </div>
            </div>
            <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Cities</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="data[Group][Group][]" multiple="multiple" class="GroupGroup form-control">
                                <option value="1513">Unitech Trade Centre</option>
                                <option value="1705">Udyog Vihar Phase 1</option>
                                <option value="391">Udyog Vihar Phase 2</option>
                                <option value="1966">Udyog Vihar Phase 3</option>
                                <option value="1513">Udyog Vihar Phase 4</option>
                                <option value="1705">Udyog Vihar Phase 5</option>
                                </select>
                            </div>
                        </div>
            </div>
            <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Users</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="data[Group][Group][]" multiple="multiple" class="GroupGroup form-control">
                                <option value="233">007chaudhary@gmail.com</option>
                                <option value="1303">0808tanya@gmail.com</option>
                                <option value="357">10ce30012@gmail.com</option>
                                <option value="1367">110040103@iitb.ac.in</option>
                                <option value="157">11111111111111</option>
                                <option value="1872">12prachijain@gmail.com</option>
                                <option value="1438">143rst@gmail.com</option>
                                <option value="1027">17rishabhr@gmail.com</option>
                                <option value="2416">1987.hema@gmail.com</option>
                                <option value="2233">246manishsharma@gmail.com</option>
                                <option value="2399">2ksaurabh@gmail.com</option>
                                <option value="1282">30althea@gmail.com</option>
                                <option value="1577">7042517568</option>
                                <option value="1538">7042848721</option>
                                <option value="2794">7579497607</option>
                                <option value="2419">77nitinj@gmail.com</option>
                                <option value="1513">7838295006</option>
                                <option value="1705">8288900040</option>
                                <option value="391">8295495303</option>
                                <option value="1966">8447731685</option>
                                <option value="158">8470946552</option>
                                <option value="2277">85.sidharth@gmail.com</option>
                                </select>
                            </div>
                        </div>
                </div>
             <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Payment Gateway</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="data[Group][Group][]" multiple="multiple" class="GroupGroup form-control">
                                <option value="1513" selected="">Paytm</option>
                                <option value="1705" selected="">Mobikwik</option>
                                <option value="391" selected="">COD</option>
                                <option value="1966" selected="">PayU money</option>
                                </select>
                            </div>
                        </div>
                </div> 
                <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">From</label>
              <div class="input-group input-group-sm ">
                <span class="input-group-addon">
                  <i class="fa fa-calendar-o"></i>
                </span>
                <input name="start" type="text" class="form-control datepicker" placeholder="02/27/2015" style="width: 300px;" value="11/06/2015" />
              </div>  
            </div>  
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">To</label>
              <div class="input-group input-group-sm">
                <span class="input-group-addon">
                  <i class="fa fa-calendar-o"></i>
                </span>
                <input name="end" type="text" class="form-control datepicker" placeholder="02/27/2015" style="width: 300px;" value="12/06/2015"/>
            </div>
            </div>         
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                  <div class="checkbox">
                    <label>
                        <input type="checkbox" name="product[send_marketing]" /> One Time Use
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                        <input type="checkbox" name="product[send_marketing2]" /> Is Combo
                    </label>
                  </div>
              </div>
            </div>

            <div class="form-group form-actions">
              <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                <a href="form.html" class="btn btn-default">Cancel</a>
                  <button type="submit" class="btn btn-success">Create Coupon </button>
              </div>
            </div>
        </form>
      </div>
    </div>

  @endsection

  @section('script')

  <script type="text/javascript">
    $(function () {

      // Form validation
      $('#new-product').validate({
        rules: {
          "product[first_name]": {
            required: true
          },
          "product[email]": {
            required: true,
            email: true
          },
          "product[address]": {
            required: true
          },
          "product[notes]": {
            required: true
          }
        },
        highlight: function (element) {
          $(element).closest('.form-group').removeClass('success').addClass('error');
        },
        success: function (element) {
          element.addClass('valid').closest('.form-group').removeClass('error').addClass('success');
        }
      });

      // Product tags with select2
      $("#product-tags").select2({
        placeholder: 'Select tags or add new ones',
        tags:["shirt", "gloves", "socks", "sweater"],
        tokenSeparators: [",", " "]
      });

      // Bootstrap wysiwyg
      $("#summernote").summernote({
        height: 240,
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['picture', 'link', 'video']],
            ['view', ['fullscreen', 'codeview']],
            ['table', ['table']],
        ]
      });

      // jQuery rating
      $('#raty').raty({
        path: 'images/raty',
        score: 4
      });

      // Datepicker
          $('.datepicker').datepicker({
            autoclose: true,
            orientation: 'left bottom',
          });

          // Minicolors colorpicker
          $('input.minicolors').minicolors({
            position: 'top left',
            defaultValue: '#9b86d1',
            theme: 'bootstrap'
          });

          // masked input example using phone input
      $(".mask-phone").mask("(999) 999-9999");
      $(".mask-cc").mask("9999 9999 9999 9999");
    });

  $('.GroupGroup').select2();
  </script>

  @endsection