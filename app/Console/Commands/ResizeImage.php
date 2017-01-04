<?php

namespace App\Console\Commands;

use App\Models\Combo;
use App\Models\Item;
use App\Models\Meal;
use Illuminate\Console\Command;

class ResizeImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bueno:resize-images {folder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resize all images in the specified folder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
  public function handle()
  {
    $folder = $this->argument('folder');

    switch($folder)
    {
      case 'meals' :

        $this->resizeMealImages();

        break;

      case 'combos' :

        $this->resizeComboImages();

        break;
    }
  }

  /**
   *
   */
  private function resizeMealImages()
  {
    $meals = Meal::all();

    foreach($meals as $meal)
    {
      if($meal->thumbnail_pic_url && $meal->thumbnail_pic_url != 'default-meal-pic.jpeg')
      {
        $this->info('Resizing :' . $meal->thumbnail_pic_url);

        resizeImage($this->argument('folder'), $meal->thumbnail_pic_url);
      }

      if($meal->display_pic_url && $meal->display_pic_url != 'default-meal-pic.jpeg')
      {
        $this->info('Resizing :' . $meal->display_pic_url);

        resizeImage($this->argument('folder'), $meal->display_pic_url);
      }
    }
  }

  /**
   *
   */
  private function resizeComboImages()
  {
    $combos = Combo::all();

    foreach($combos as $combo)
    {
      if($combo->thumbnail_pic_url && $combo->thumbnail_pic_url != 'default-meal-pic.jpeg')
      {
        $this->info('Resizing :' . $combo->thumbnail_pic_url);

        resizeImage($this->argument('folder'), $combo->thumbnail_pic_url);
      }

      if($combo->display_pic_url && $combo->display_pic_url != 'default-meal-pic.jpeg')
      {
        $this->info('Resizing :' . $combo->display_pic_url);

        resizeImage($this->argument('folder'), $combo->display_pic_url);
      }
    }
  }
}
