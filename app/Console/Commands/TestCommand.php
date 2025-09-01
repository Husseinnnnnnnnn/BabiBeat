<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Subscription;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-command';

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
        $new_subscription = new subscription();
        $new_subscription->type = 'gwg';
        $new_subscription->price = 12;
        $new_subscription->billing_cycle = 'monthly';
        $new_subscription->is_active = true;
        $new_subscription->save();

        


    }
}
