<?php namespace Bueno\Notifications;

use Illuminate\Support\ServiceProvider;

class FlashServiceProvider extends ServiceProvider {

  public function register()
  {
    $this->app->bindShared('flash', function()
    {
      return $this->app->make('Bueno\Notifications\FlashNotifier');
    });
  }

}

