<?php

namespace App\Console\Commands;

use App\Events\SyncStockWithTorqusEvent;
use Illuminate\Console\Command;
use Bueno\Repositories\DbMembershipRepository as MembershipRepo;

class UpdateMembership extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bueno:membership-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the membership Ids of All Users.';

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
      $membershipRepo = new MembershipRepo;
      $membershipRepo->updateAllUserMembership();
    }
}
