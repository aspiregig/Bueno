<?php

namespace App\Console\Commands;

use Bueno\Repositories\DbOrderRepository;
use Illuminate\Console\Command;

class MarkOrdersAsSettled extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bueno:mark-settled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marks orders as settled after live date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DbOrderRepository $orderRepo)
    {
        parent::__construct();

        $this->orderRepo = $orderRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $order_ids = $this->orderRepo->getOrdersAfterDate('2016-03-01 00:00:00','2016-03-31 23:59:59')->pluck('id')->toArray();

      $this->orderRepo->markOrdersAsSettled($order_ids);
    }
}
