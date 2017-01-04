<?php namespace Bueno\Validations;

use Illuminate\Validation\Factory;

abstract class Validator {

    protected $validator;

    function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    public function fire($inputs)
    {
        $messages = [
            'image'                 => 'Please upload a valid image.',
            'duplicate_entry'       => 'The :attribute cannot be duplicate.',
            'image_min_size'        => 'The Image size is  not as the guided size.',
            'image_max_size'        => 'The Image size is  not as the guided size.',
            'file_size_in_between'  => 'The Image file size is  not as the guided.',
            'disallow_future_date'  => 'You cannot select future date for :attribute',
            'min_quantity'          => 'The quantity of :attribute cannot exceed the maximum limit.',
            'at_least_one_zero'     => 'At Least One membership should have min 0.',
            'unique_area_of_city'   => 'Area with this name Already Exists in the City',
        ];

        $validation = $this->validator->make($inputs,static::rules(), $messages);

        if($validation->fails()) throw new ValidationException($validation->messages());

        return true;
    }
}