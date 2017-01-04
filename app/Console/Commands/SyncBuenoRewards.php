<?php

namespace App\Console\Commands;

use Log;
use Illuminate\Console\Command;
use Bueno\Repositories\DbOrderRepository as OrderRepo;

class SyncBuenoRewards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bueno:sync-rewards {--start=} {--end=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Bueno Rewards';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OrderRepo $orderRepo)
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
        $start = $this->option('start');
        $end = $this->option('end');
        if($start && $end)
        {
            $orders = $this->orderRepo->getLoyalOrders($start,$end);
            foreach ($orders as $order) {
                $msg = $this->orderRepo->creditReward($order);
                $this->info($msg);
                Log::info($msg);
            }
        }

    }
}
