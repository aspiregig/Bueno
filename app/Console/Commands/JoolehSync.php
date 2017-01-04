<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Bueno\Services\JoolehAPI;
use Log;
use App\Models\Order;
use Bueno\Repositories\DbOrderRepository as OrderRepo;

class JoolehSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bueno:jooleh-sync  {--start=} {--end=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Infomormation From Jooleh';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(JoolehAPI $jooleh,OrderRepo $orderRepo)
    {
        parent::__construct();
        $this->jooleh = $jooleh;
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

        Order::where('created_at','>=',$start)->where('created_at','<=',$end)->chunk(500, function($orders) {
            foreach ($orders as $order) {
                if($order->joolehLog)
                {
                    $jooleh_response = $this->jooleh->getOrderDetails($order);
                    if($jooleh_response)
                    {
                        $jooleh_response = $jooleh_response->getBody()->getContents();
                        $jooleh_response = json_decode($jooleh_response,true);
                        if(isset($jooleh_response['oid']))
                        {
                            $this->orderRepo->updateOrderJooleh($jooleh_response);
                            $this->info('Order Id ' . $order->id .' Updated.');
                            Log::info('Order Id ' . $order->id .' Updated');
                        }
                        else
                        {
                             $this->error('Order Id ' . $order->id .' Empty response From Jooleh.');
                        Log::error('Order Id ' . $order->id .' Empty response From Jooleh.');
                        }
                    }
                    else
                        $this->error('Order Id ' . $order->id .' No response From Jooleh.');
                        Log::error('Order Id ' . $order->id .' No response From Jooleh.');
                }
                else
                {
                        $this->error('Order Id ' . $order->id .' No Jooleh Log.');
                        Log::error('Order Id ' . $order->id .' No Jooleh Log.');
                }
            }
        });


        $this->info('Done');


    }
}
