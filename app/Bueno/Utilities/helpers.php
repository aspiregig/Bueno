<?php

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\File;

function get_upload_file($file_name, $mime_type = "image/jpeg", $path)
{
  $path =  base_path() . '/uploads/' . $path . '/';

    if(!empty($file_name))
    {
      $photo_path = $path . $file_name;

      if(file_exists($photo_path))
      {
        $path = $photo_path;
        $file = new File($path);
        $mime_type = $file->getMimeType();
      }
    }

    header("Content-Type: ". $mime_type);
    // header("Cache-Control: max-age=2592000 ");
    // header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));
    return readfile($path);
}

function upload_file($file, $path)
{
  $path =  base_path() . '/uploads/' . $path . '/';
  
    $filename = time() . str_replace(' ', '', $file->getClientOriginalName());

    $file->move($path, $filename);

  return $filename;
}

function slug($title, $model) 
{
  $slug = Str::slug($title);

  $slugCount = count( $model->whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->get() );

  return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
}

function calculateUsablePoints($cart_items, $available_points)
{
  $cart_amount = total_cart_amount($cart_items);
  $usable_points = $available_points;

  if($usable_points > ($cart_amount/2))
    $usable_points = $cart_amount/2;

  return ceil($usable_points);
}

function total_cart_amount($cart_items)
{
  $total = 0;

  foreach($cart_items as $cart_item)
  {
    $total += $cart_item->item->itemable->discount_price * $cart_item->quantity;
  }

  return $total;
}

function applyTax($amount, $tax)
{
  $total = $amount * $tax * .01;

  return round($total, 2);
}

/** SORTING ORDeRs TABLE */

function sort_orders_by($column)
{ 
  $query = '';

  foreach (Request::except(['sortBy','direction','ids']) as $key => $value) 
  {
    $query .= '&' . $key . '=' . $value;
  }

  $query .= '#datatable-orders';

  $direction = ( Request::get('direction') == 'asc' )  ? 'desc' : 'asc';

  return route('admin.orders',['sortBy' => $column , 'direction' => $direction , $query ]);
}

function sort_users_by($column)
{ 
  $query = '';

  foreach (Request::except(['sortBy','direction','ids']) as $key => $value) 
  {
    $query .= '&' . $key . '=' . $value;
  }

  $query .= '#datatable-user';

  $direction = ( Request::get('direction') == 'asc' )  ? 'desc' : 'asc';

  return route('admin.users', ['sortBy' => $column , 'direction' => $direction , $query ]);
}

function sort_coupons_by($column)
{ 
  $query = '';

  foreach (Request::except(['sortBy','direction','ids']) as $key => $value) 
  {
    $query .= '&' . $key . '=' . $value;
  }

  $query .= '#datatable-coupons';

  $direction = ( Request::get('direction') == 'asc' )  ? 'desc' : 'asc';

  return route('admin.coupons', ['sortBy' => $column , 'direction' => $direction , $query ]);
}

/**
 * @param $key
 * @return string
 */
function get_sort_icon($key)
{
  $class = 'sort-icon ';

  if(Request::has('sortBy') && Request::get('sortBy') == $key )
  {
    $direction = Request::has('direction') ? Request::get('direction') : 'asc';

    $class .= $direction == 'asc' ? 'ion-arrow-up-b' : 'ion-arrow-down-b';
  }

  return $class;
}

function cdn_asset($path)
{
  return config('resources.static_assets.base_url').$path."?v=".config('resources.static_assets.version');
}


function resizeImage($path, $file)
{
  $path = base_path() . '/uploads/' . $path . '/';

  $image_path = $path . '/original/' . $file;

  if(file_exists($image_path))
  {
    // small size - width - 100
    $small_size = Image::make( $image_path )->resize(100, null, function ($constraint) {
      $constraint->aspectRatio();
    });

    $small_size->save($path . '/small/' . $file, 100);

    // medium - width - 400
    $medium_size = Image::make( $image_path )->resize(400, null, function ($constraint) {
      $constraint->aspectRatio();
    });

    $medium_size->save($path . '/medium/' . $file, 100);

    // large image - width - 1000
    $large_size = Image::make( $image_path )->resize(1000, null, function ($constraint) {
      $constraint->aspectRatio();
    });

    $large_size->save($path . '/large/' . $file, 100);
  }
}



