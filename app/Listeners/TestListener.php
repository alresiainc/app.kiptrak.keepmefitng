<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

use App\Notifications\TestNofication;
use App\Events\TestEvent;
use App\Mail\TestMail;

class TestListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TestEvent $event)
    {
        //expecting event

        //can decide to store in db here, or do it in controller 

        // dd($event->invoiceData['user']->name); //akon

        $invoiceData = $event->invoiceData;
        $customer_email = $invoiceData['customer']->email;

        // $invoiceData = [
        //     'order' => $order,
        //     'customer' => $order->customer,
        //     'mainProducts_outgoingStocks' => $mainProducts_outgoingStocks,
        //     'orderbump_outgoingStock' => $orderbump_outgoingStock == '' ? '' : $orderbump_outgoingStock,
        //     'upsell_outgoingStock' => $upsell_outgoingStock == '' ? '' : $upsell_outgoingStock,
        // ];

        //send email
        Mail::to($customer_email)->send(new TestMail($invoiceData));

    }
}
