<?php

namespace FireApps\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use FireApps\Repositories\ProductRepository;
use Shopify;

class ProcessShopProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $products;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($products)
    {
        $this->products = $products;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $repository = app(ProductRepository::class);
        foreach($this->products as $product) {
            $product = $repository->updateOrCreate(['id' => $product['id']], $product);
            $message = Shopify::getMetaMessage($product);

            $product->message = $message;
            $product->save();
        }
    }
}
