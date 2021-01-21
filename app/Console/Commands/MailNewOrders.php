<?php

namespace App\Console\Commands;

use App\Mail\NewOrderRegistered;
use App\Mail\NewOrdersRegistered;
use App\Models\Order;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MailNewOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:stackNewOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send mail for newly registered order';

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
     * @throws \Exception
     */
    public function handle()
    {
        $dateNow = new Carbon('Europe/Paris');

        // Get all active orders
        $ordersActive = Order::whereIn('status', [Order::STATUS_ACTIVE_ORDER, Order::STATUS_IN_PROGRESS_ORDER])
                                ->get();

        $orders = collect();
        foreach($ordersActive as $order){
            $dateEntered = new Carbon($order->date_entered);

            // If order is older than 1h
            $diff = $dateEntered->diffInMinutes($dateNow, false);

            // Pass order to in_progress status
            if($diff < config('app.minutesBeforeMail')){
                $orders->push($order);
            }
        }

        if($orders->count() > 0){
            // Send mail to admin when new orders are registered
            $emails = User::getAdmins()->whereNotNull('email')->get(['email']);
			Mail::to($emails)
				->queue(new NewOrdersRegistered($orders));
        }
    }
}
