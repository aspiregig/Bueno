<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Bueno Config
    |--------------------------------------------------------------------------
    |
    | This file contain all the config variables used in the application
    |
    */

  'groups' => [
    '1' => 'Admin',
    '2' => 'User',
    '3' => 'Kitchen Manager'
  ],
  'image_size' => [
    'item_image_min_size' => 400,
    'item_image_max_size' => 400,
    'item_thumb_min_size' => 400,
    'item_thumb_max_size' => 400
  ],
    'order_status' => [
      'initiated' => '1',
      'in_kitchen' =>'2',
      'dispatched' => '3',
      'delivered' => '4',
      'pending' => '5',
      'cancelled' => '6',
      'packed' => '7'
    ],

    'jooleh' => [
      'admin_token'     => '1zvweU2jR4kSGi9CHvkb',
      'admin_username'  => 'bueno.test',
      'user_id' => 12887,
    ],

    'default_auth' => [
        'user_id'     => '1'
    ],

    'gender'  => [
      1 => 'Male',
      2 => 'Female'
    ],

    'jooleh_status' => [
      '1'   => 'Confirmed',
      '2'   => 'Dispatched',
      '3'   => 'Delivered',
      '4'   => 'Rejected',
      '5'   => 'Cancelled',
      '6'   => 'Pending'
    ],

    'sms_api' => [
      'username' => 'neshattr',
      'password' => 'Ad5b24760f368261fd148ed8195b501db'
    ],

    'voice_otp_api' => [
      'key' => '5c601258-7efb-11e5-9a14-00163ef91450'
    ],

    'payment_modes' => [
      1 => 'ebs' ,
      2 => 'payumoney' ,
      3 => 'paytm',
      4 => 'cod'
    ],

    'pay_u_money' => [
      'merchant_key'  => 'JzCyXY',
      'salt'  => 'UQeJDYOB',
      'base_url'  => 'https://secure.payu.in/_payment'
    ],

    'pay_u_money_testing' => [
        'merchant_id' => '4933935',
        'merchant_key'  => 'jAxQ0o',
        'salt'  => '6jqf5QKy',
        'base_url'  => 'https://test.payu.in/_payment'
    ],

    'paytm' => [
      'industry_type_id'  => 'Retail128',
      'channel_id'  => 'WEB',
      'merchant_website' => 'Buenoweb',
      'merchant_mid'  => 'buenok94734983487107',
      'domain' => 'secure.paytm.in',
      'environment' => 'PROD',
      'merchant_key' => '5oMRUlC#@jOq9&W%',
      'post_url'  => 'https://secure.paytm.in/oltp-web/processTransaction'
    ],

    'paytm_testing' => [
        'industry_type_id'  => 'Retail',
        'channel_id'  => 'WEB',
        'environment' => 'TEST',
        'merchant_website' => 'Buenoweb',
        'merchant_mid'  => 'Buenoo62370911941128',
        'domain' => 'pguat.paytm.com',
        'merchant_key' => 'A6hFbZBmDl3JK@w!',
        'post_url'  => 'https://pguat.paytm.com/oltp-web/processTransaction'
    ],

    'ebs' => [
      'secret_key' => 'f59c129cef206892a5c6268e40cf8de8',
      'account_id'  => 17993,
      'channel' => 0,
      'mode' => "LIVE",
      'return_url'  => 'http://bueno.kitchen/pages/order_confirm_process_ebs',
      'action_url'  => 'https://secure.ebs.in/pg/ma/payment/request'
    ],

    'payzapp' => [
      'merchant_name' => 'Bueno Food Pvt Ltd',
      'merchant_id' => '71939326361484916461',
      'merchant_app_id' => '6471',
      'secret_key'  => '64719393263655012658',
    ],

    'razorpay' => [
        'key' => 'rzp_live_qwB115wRJTGvSb',
        'secret' => '33Qn6W005WXBJGo9e7dyJvCy',
    ],

    'razorpay_testing' => [
        'key' => 'rzp_test_hor6h8ftf1nqTr',
        'secret' => 'S93wTDqOdQY6Tre3VHADOdok',
    ],

    'membership' => [
      1 => 'Bronze',
      2 => 'Gold',
      3 => 'Platinum'
    ],

    'membership_points' => [
      1 => 50,
      2 => 100,
      3 => 200
    ],
    'update_order_status' =>[
      2 => 'In Kitchen',
      6 => 'Cancelled',
      5 => 'Pending'
    ]
    ,
  'coupon_messages_user' => [
    'status_disabled' => 'This Coupon is Expired or Invalid.',
  ],

  'social' => [
    'facebook' =>'https://www.facebook.com/buenokitchen/',
    'twitter' => 'https://twitter.com/buenokitchen',
    'gplus' => 'https://www.google.com',
    'linkedIn' => 'https://www.linkedin.com/company/2949452',
    'zomato' => 'https://www.zomato.com/buenokitchen',
    'instagram' =>'https://www.instagram.com/buenokitchen/',
    'playStore' => 'https://play.google.com/store/apps/details?id=com.bueno.kitchen&hl=en',
    'iTunes' =>'https://itunes.apple.com/in/app/bueno-goodness-inside/id1061452349?mt=8'

  ],
    'message' => [
        '404_heading' => "Bueno couldn't find it.",
        '404_text' => 'This is not the page you are looking for.',
        '500_heading' => "",
        '500_text' => "OOPs !! Some Error Occurred",
    ],

  'site'  => [
      'logo_text' => 'Good Food<br>Delivered to your doorstep<br>Open from 9am till 4am.',
      'catering_phone' => '01139586767',
      'careers_email' => 'careers@bueno.kitchen',
      'catering_email' => 'catering@bueno.kitchen',
      'catering_text_1' => 'We offer a completely customizable 60 item catering menu spanning 7 cuisines!',
      'catering_text_2' => 'Call us, email us or submit a query and we will get right back to you.',
      'copyright_text' => 'All Rights Reserved &copy; Bueno Foods Pvt. Ltd. |',
      'support_email'   => 'bueno@bueno.kitchen',
      'phone' => '01139586767',
      'footer_phone'  => '01139586767',
      'corporate_phone' => '01139586767',
      'enquiry_phone' => '01139586767',
      'enquiry_email' => 'info@bueno.kitchen',
      'investment_phone'  => '91-98195-11928',
      'email' => 'bueno@bueno.kitchen',
      'bcc_email' => 'bueno@bueno.co.in',
      'referral_email_text' => 'I really enjoyed my meal at Bueno made by 5 Star Chefs in their own kitchen and thought you would love it too! Try their online delivery service anytime between 9 am to 4 am in Gurgaon at www.bueno.kitchen',
      'live_date' => '2015-09-01 00:00:00'
  ],

  'email' => [
    'text_1' => 'A carefully curated 7 Cuisine Menu <br> for every mood and occasion<br><br>',
    'text_2'  => 'Delivery from 9.00 am till 4.00 am <br> to satiate hunger at any time <br> of the day or night<br><br>',
    'text_3' => '5 star chefs <br> with a promise of quality<br>',
    'phone' =>  '01139586767'
  ],

  'image' => [
      'slider_min_width' => '1000',
      'slider_min_height' => '500',
      'slider_max_width' => '2000',
      'slider_max_height' => '1000',
      'slider_ration_1' => '2.85',
      'slider_ration_2' => '1',
      'slider_ration_3' => '0.1',
      'slider_file_size_1' => '60',
      'slider_file_size_2' => '1024',
      'banner_min_width' => '1000',
      'banner_min_height' => '250',
      'banner_max_width' => '2000',
      'banner_max_height' => '500',
      'banner_ration_1' => '3.77',
      'banner_ration_2' => '1',
      'banner_ration_3' => '0.1',
      'banner_file_size_1' => '60',
      'banner_file_size_2' => '1024',
      'item_min_width' => '200',
      'item_min_height' => '200',
      'item_max_width' => '1000',
      'item_max_height' => '1000',
      'item_thumb_min_width' => '200',
      'item_thumb_min_height' => '200',
      'item_thumb_max_width' => '1000',
      'item_thumb_max_height' => '1000',
      'item_ration_1' => '1.55',
      'item_ration_2' => '1',
      'item_ration_3' => '0.1',
      'item_file_size_1' => '60',
      'item_file_size_2' => '1024',
      'ngo_min_width' => '90',
      'ngo_min_height' => '40',
      'ngo_max_width' => '150',
      'ngo_max_height' => '90',
      'ngo_ration_1' => '2.01',
      'ngo_ration_2' => '1',
      'ngo_ration_3' => '.1',
      'ngo_file_size_1' => '10',
      'ngo_file_size_2' => '500',
  ],
  'torqus' => [
      'username' => 'systemuser',
      'password' => 'box1099',
      'type' => 'PARTNER',
      'companyId' => 2729,
  ],
    'item_status' => [
    '0' => 'Disabled',
    '1' => 'Active',
    '2' => 'Coming Soon',
],
  'source' => [
    'android' => 1,
    'bueno' => 2,
    'ios' => 4,
    'web' => 7,
    'quick'=> 5
  ],
  'color_class' => [
    '3' => 'label-primary',
    '2' => 'label-warning',
    '1' => 'label-danger',
    '4' => 'label-success',
    '7' => 'label-info',
    '6' => 'label-danger',
    '5' => 'label-danger',
  ],
  'intercom'  => [
    'testing' => 'wuxzdccz',
    'live'  => 'qlbney9q'
  ],

  'stock' => [
    'threshold' => 2
  ],
  'dashboard_refresh_rate' => 120,

  'api' => [
    'minimum_version' => "1",
    'current_version' => "1"
  ]

];