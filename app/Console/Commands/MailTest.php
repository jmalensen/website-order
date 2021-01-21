<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MailTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conselio:testmail {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test l\'envoie d\'un email';

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

        Mail::send([], [], function ($message) {
            $message->to($this->argument('email'))
            ->subject('Test email')
            ->setBody('Hi, welcome user!'); // assuming text/plain
        });
    }
}
