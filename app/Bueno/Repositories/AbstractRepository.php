<?php namespace Bueno\Repositories;

/**
 * Class AbstractRepository
 * @package Bueno\Repositories
 */
abstract class AbstractRepository{

  /**
   * @return mixed
   */
  public function getAll()
  {
    return $this->model->get();
  }

  /**
   * @return $this
   */
  public function scopeActive()
  {
    $this->model = $this->model->where('status', 1);

    return $this;
  }

  /**
   * @param $column
   * @param $value
   * @return mixed
   */
  public function getByColumn($column, $value)
  {
    return $this->model->where($column, $value)->first();
  }

  /**
   * @param int $perPage
   * @return mixed
   */
  public function paginate($perPage = 15)
  {
    return $this->model->paginate($perPage);
  }

  /**
   * get user by id
   *
   * @param $user_id
   * @return mixed
   */
  public function getById($user_id)
  {
    return $this->model->find($user_id);
  }

  /**
   * @param array $fields
   * @return $this
   */
  public function includeFields($fields = [])
  {
    foreach($fields as $filed)
    {
      $this->model = $this->model->with($filed);
    }

    return $this;
  }
}
