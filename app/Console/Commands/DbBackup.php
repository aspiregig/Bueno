<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use AWS;
use Bueno\Repositories\DbOrderRepository as OrderRepo;


class DbBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bueno:db-backup {--size=} {--start=} {--end=} {--revert=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bueno Databases Backup to AWS';
    protected $bucket = 'bueno1';

    protected $orderRepo;
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
        $size = $this->option('size');

        $start = $this->option('start');

        $end = $this->option('end');

        $revert = $this->option('revert');

        if($revert)
        {
            return $this->orderRepo->revertOrder();
        }

        if($start && $end && $size)
        {
            return $this->backupCustom($size,$start,$end);
        }

        $this->path = 'storage/dumps/';
        //Database compression and upload
        try {
            $databaseDriver = config('database.default');
            $this->db_config = config('database.connections.' . $databaseDriver);
            $this->file = $this->db_config['database'] . '_' . time() . '.gz';
            $this->fullPath = $this->path . $this->file;
            $this->key = 'database-backups/' . $this->file;
            $bool = $this->createDbZip();
            if($bool) {
                $bool = $this->uploadToS3();
                if($bool) {
                    $this->info('Uploaded to S3');
                } else {
                    $msg = 'Error occured while uploading to S3';
                    $this->info($msg);
                    Log::info($msg);
                }
                $this->deleteZip();
            }
        } catch(Exception $e) {
            Log::info($e->getMessage());
        }
        $this->info('Done');
    }

    public function createDbZip() {
        $cmd = sprintf('mysqldump --user=%s --password=%s %s | gzip -9 > %s',
                escapeshellarg($this->db_config['username']),
                escapeshellarg($this->db_config['password']),
                escapeshellarg($this->db_config['database']),
                escapeshellarg($this->fullPath)
            );
        exec($cmd, $output, $return);
        if(!$return) {
            $msg = 'Database zip created';
            $status = true;
            //$this->sendBackupToTeam($this->fullPath);
        } else {
            $msg = 'Database zip not created';
            $status = false;
        }
        $this->info($msg);
        Log::info($msg);
        return $status;
    }

    public function uploadToS3() {
        $this->s3 = AWS::createClient('s3');
        $out = $this->s3->putObject(array(
            'Bucket'     => $this->bucket,
            'Key'        => $this->key,
            'SourceFile' => $this->fullPath,
        ));
        Log::info($out);
        return $this->confirmUpload();
    }

    public function confirmUpload() {
        return $this->s3->doesObjectExist($this->bucket, $this->key);
    }

    public function deleteZip() {
        unlink($this->fullPath);
        $msg = $this->file . ' zip deleted';
        $this->info($msg);
        Log::info($msg);
    }

    public function backupCustom($size,$start,$end)
    {
        $this->orderRepo->customBackup($size,$start,$end);

        return true;
    }
}