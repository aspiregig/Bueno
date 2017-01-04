<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Bueno\Repositories\DbUserRepository as UserRepo;


class SyncUserCouponStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bueno:sync-user-coupon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Users Coupon Status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserRepo $userRepo)
    {
        parent::__construct();
        $this->userRepo = $userRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->userRepo->updateUserCouponStatus();
    }
}
