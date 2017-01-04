<?php namespace Bueno\Validations;

class CreateSliderValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    return [
        'sequence' =>'required|numeric|min:1',
        'link_url' => 'url',
        'image_url' => 'image_min_size:'.config('bueno.image.slider_min_width').','.config('bueno.image.slider_min_height').'|'.'image_max_size:'.config('bueno.image.slider_max_width').','.config('bueno.image.slider_max_height').'|'.'file_size_in_between:'.config('bueno.image.slider_file_size_1').','.config('bueno.image.slider_file_size_2').',|image_ratio:'.config('bueno.image.slider_ration_1').','.config('bueno.image.slider_ration_2').','.config('bueno.image.slider_ration_3'),
    ];
  }
}
