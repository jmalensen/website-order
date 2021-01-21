<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrdersRegistered extends Mailable
{
    use Queueable, SerializesModels;
    public $orders;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $orders = $this->orders;

        $this->subject( __('Liste des nouvelles commandes') );

        return $this->view('emails.newOrders')->text('emails.newOrders_plain')->with(compact('orders'));
    }
}
