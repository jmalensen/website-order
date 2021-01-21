<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ChangeStatusOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change:statusOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to change status of order (active to in progress)';

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
        $ordersActive = Order::where('status', Order::STATUS_ACTIVE_ORDER)
                                ->get();

        foreach($ordersActive as $order){
            $dateEntered = new Carbon($order->date_entered);

            // If order is older than 1h
            $diff = $dateEntered->diffInMinutes($dateNow, false);

            // Orders are changed automatically at 11:00
            $now = Carbon::now('Europe/Paris')->format('H:i:s');
            $lastTimeToOrder = config('app.lastTimeToOrder');

            // Pass order to in_progress status
            if($diff >= 60 || $now > $lastTimeToOrder){
                $order->status = Order::STATUS_IN_PROGRESS_ORDER;
                $order->save();
            }
        }
    }
}
