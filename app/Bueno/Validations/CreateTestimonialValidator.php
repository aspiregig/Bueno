<?php namespace Bueno\Validations;

class CreateTestimonialValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    return [
        'full_name' => 'required|min:4|max:20',
        'content' => 'required|min:20|max:150',
        'special_text' => 'required|min:5|max:25'
    ];
  }
}
