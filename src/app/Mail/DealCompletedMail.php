<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DealCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public $buyer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Product $product, $buyer)
    {
        $this->product = $product;
        $this->buyer = $buyer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('【取引完了】' . $this->product->name)
            ->markdown('deal_completed');
    }
}
