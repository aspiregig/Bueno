<?php

namespace App\Providers;

use Bueno\Utilities\Md5Hasher;
use Illuminate\Support\ServiceProvider;

class Md5HasherServiceProvider extends ServiceProvider {

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register() {
    $this->app['hash'] = $this->app->share(function () {
      return new Md5Hasher();
    });

  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides() {
    return array('hash');
  }

}