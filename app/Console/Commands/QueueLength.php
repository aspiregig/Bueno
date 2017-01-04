<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
//use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Contracts\Queue\ShouldQueue;
use Queue;

class QueueLength extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bueno:queue-length';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets number of items in redis queue';

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

        echo (Queue::getRedis()->command('LLEN',['queues:default']));

    }
}