<?php namespace Bueno\Validations;

use Request;
use App\Models\Goody;

class GoodyValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    $request = Request::instance();

    $rules =  [
        'worth' => 'numeric|min:0',
        'quantity' => 'required|numeric|min:0',
        'description' => 'required|max:200',
          'image_url' => 'image_min_size:'.config('bueno.image.item_min_width').','.config('bueno.image.item_min_height').'|'.'image_max_size:'.config('bueno.image.item_max_width').','.config('bueno.image.item_max_height').'|'.'file_size_in_between:'.config('bueno.image.item_file_size_1').','.config('bueno.image.item_file_size_2').',|image_ratio:'.config('bueno.image.item_ration_1').','.config('bueno.image.item_ration_2').','.config('bueno.image.item_ration_3'),


    ];


    switch($request->method())
    {
      case 'POST':

        $rules['name'] = 'required|unique:goodies,name';

        break;

      case 'PATCH':

        $goody = Goody::find($request->input('id'));

        $rules['name'] =  'required|unique:goodies,name,' . $goody->id . ',';

        break;

      default:break;
    }


    return $rules;
  }

}
