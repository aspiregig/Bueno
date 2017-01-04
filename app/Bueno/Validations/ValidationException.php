<?php namespace Bueno\Validations;

class ValidationException extends \Exception{

    protected $errors;

    /**
     * @param string $errors
     */
    function __construct($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

  /**
   * @return array
   */
    public function getMessages()
    {
      $messages = [];

      foreach($this->errors->all() as $error )
      {
        $messages[]['message'] = $error;
      }

      return $messages;
    }
}