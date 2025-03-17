<?php

namespace App\Jobs;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;

    public $products;

    public $recipientType;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order, array $products, string $recipientType)
    {
        $this->order = $order;
        $this->products = $products;
        $this->recipientType = $recipientType;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        if ($this->recipientType == 'customer') {
            Mail::to($this->order->customer_email)->send(new OrderConfirmation($this->order, $this->products, 'customer'));
        } else {
            $users = User::all();
            foreach ($users as $user) {
                Mail::to($user->email)->send(new OrderConfirmation($this->order, $this->products, 'admin'));
            }
        }
    }
}
