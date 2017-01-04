<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Bueno\Mailers\UserMailer;
use Bueno\Repositories\DbOrderRepository as OrderRepo;

class OrderReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bueno:order-report {--email=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email All Orders Report';

    protected $orderRepo,$mailer;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OrderRepo $orderRepo,UserMailer $mailer)
    {
        parent::__construct();
        $this->orderRepo = $orderRepo;
        $this->mailer = $mailer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->orderRepo->exportOrders();

        $email = $this->option('email');


        if(!$email)
        {
            $email='pankajb@gmail.com';
        }

        $view = 'emails.admins.all_orders';

        $data = [];

        $this->mailer->sendOrderExport($email, 'Orders Export', $view, $data, storage_path().'/dumps/orders-export.csv');


    }
}
