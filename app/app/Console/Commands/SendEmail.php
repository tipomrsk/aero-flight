<?php

namespace App\Console\Commands;

use App\Mail\ApprovedOrderTravel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'play';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Play with Laravel';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Mail::to('recipient@example.com')->send(new ApprovedOrderTravel());

        $this->info('ApprovedOrderTravel email sent successfully.');
    }
}
