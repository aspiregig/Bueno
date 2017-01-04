<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends BaseModel{

  protected $fillable = ['name','sequence','status'];

  public $timestamps = false;

  protected $repo = 'Bueno\Repositories\ItemRepository';
}
