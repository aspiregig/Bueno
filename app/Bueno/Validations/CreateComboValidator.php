<?php namespace Bueno\Validations;

use Request;
use App\Models\Combo;
use App\Models\Item;

class CreateComboValidator extends Validator{

   /**
   * validation rules for create meal form
   *
   * @return array
   */
  public function rules()
  {
    $request = Request::instance();

      $rules =  [
        'name' => 'required',
        'serves' => 'required|numeric',
        'type' => 'required|numeric',
        'spice_level' => 'required|numeric',
        'cuisine_id' => 'required|numeric',
        'category_id' => 'required|numeric',
        'weight' => 'required|numeric|min:0',
        'original_price' => 'required|numeric|min:1',
        'food_cost' => 'required|numeric|min:1',
        'total_cost' => 'required|numeric|min:1',
        'discount_price' => 'max:'.$request->get('original_price').'|numeric|min:0',
        'description' => 'required|min:5|max:200',
        'long_description' => 'required|min:5|max:500',
        'status' => 'required',
        'meals'  => 'required|duplicate_entry',
        'display_pic_url' => 'image_min_size:'.config('bueno.image.item_min_width').','.config('bueno.image.item_min_height').'|'.'image_max_size:'.config('bueno.image.item_max_width').','.config('bueno.image.item_max_height').'|'.'file_size_in_between:'.config('bueno.image.item_file_size_1').','.config('bueno.image.item_file_size_2').',|image_ratio:'.config('bueno.image.item_ration_1').','.config('bueno.image.item_ration_2').','.config('bueno.image.item_ration_3'),
        'thumbnail_pic_url' => 'image_min_size:'.config('bueno.image.item_thumb_min_width').','.config('bueno.image.item_thumb_min_height').'|'.'image_max_size:'.config('bueno.image.item_thumb_max_width').','.config('bueno.image.item_thumb_max_height').'|'.'file_size_in_between:'.config('bueno.image.item_file_size_1').','.config('bueno.image.item_file_size_2').',image_ratio:'.config('bueno.image.item_ration_1').','.config('bueno.image.item_ration_2').','.config('bueno.image.item_ration_3'),
      ];


     switch($request->method())
     {
       case 'POST':

         $rules['slug'] = 'required|unique:combos,slug';
         $rules['product_sku'] = 'required|unique:items,product_sku';
         break;

       case 'PATCH':

         $combo = Combo::find($request->input('id'));
         $item = Item::where('itemable_id',$combo->id)->where('itemable_type','App\Models\Combo')->first();


         $rules['slug'] =  'required|unique:combos,slug,' . $combo->id . ',';
         $rules['product_sku'] = 'required|unique:items,product_sku,'.$item->id.',';


         break;

       default:break;
     }


      return $rules;
  }
}
