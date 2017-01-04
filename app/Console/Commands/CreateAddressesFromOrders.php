<?php

namespace App\Console\Commands;

use App\Models\Address;
use App\Models\Order;
use Bueno\Repositories\DbOrderRepository;
use Illuminate\Console\Command;

class CreateAddressesFromOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bueno:create-addresses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates non-existing addresses from orders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DbOrderRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orders = Order::all();

        foreach($orders as $order)
        {
            $address = Address::where('address', $order->delivery_address)
                              ->where('user_id', $order->user_id)
                              ->where('area_id', $order->delivery_area_id)
                              ->get();

            if($address->count() == 0)
            {
              Address::create([
                  'address_name'  => 'New Address',
                  'address' => $order->delivery_address,
                  'user_id' => $order->user_id,
                  'area_id' => $order->delivery_area_id
              ]);

              $this->info('Created address - ' . $order->delivery_address);
            }
        }
    }
}
