<?php namespace Bueno\Validations;

class CreateBannerValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    return [
        'content' => 'required',
        'link_url' => 'url',
        'display_pic_url' => 'image_min_size:'.config('bueno.image.banner_min_width').','.config('bueno.image.banner_min_height').'|'.'image_max_size:'.config('bueno.image.banner_max_width').','.config('bueno.image.banner_max_height').'|'.'file_size_in_between:'.config('bueno.image.banner_file_size_1').','.config('bueno.image.banner_file_size_2').',|image_ratio:'.config('bueno.image.banner_ration_1').','.config('bueno.image.banner_ration_2').','.config('bueno.image.banner_ration_3'),
    ];
  }
}
