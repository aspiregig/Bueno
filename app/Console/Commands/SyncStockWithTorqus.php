<?php

namespace App\Console\Commands;

use App\Events\SyncStockWithTorqusEvent;
use Illuminate\Console\Command;

class SyncStockWithTorqus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bueno:sync-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the stocks with torqus API';

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
      event(new SyncStockWithTorqusEvent);
    }
}
