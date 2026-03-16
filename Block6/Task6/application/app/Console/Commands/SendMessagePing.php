<?php

namespace Task6\App\Console\Commands;

use Illuminate\Console\Command;
use Task6\App\Jobs\MessagePingJob;

class SendMessagePing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $messagesCount = 10;
    protected $signature = 'app:send-message-ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {    
        for ($i = 1; $i <= $this->messagesCount; $i++) {
           MessagePingJob::dispatch($i);
        }
    }
}
