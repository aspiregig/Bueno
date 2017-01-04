<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuisine extends BaseModel{

  protected $fillable = ['name','status','sequence'];

    protected $repo = 'Bueno\Repositories\DbCommonRepository';

    public function meals()
    {
      return $this->hasMany('App\Models\Meal');
    }
}