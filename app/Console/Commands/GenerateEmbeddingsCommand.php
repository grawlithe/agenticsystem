<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('embeddings:generate')]
#[Description('Generate embeddings for all Products, Customers, Orders, and Order Items using the Nomic local model.')]
class GenerateEmbeddingsCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->generateFor(Product::class, 'Products');
        $this->generateFor(Customer::class, 'Customers');
        $this->generateFor(Order::class, 'Orders');
        $this->generateFor(OrderItem::class, 'Order Items');

        $this->info('All embeddings generated successfully!');
    }

    /**
     * Generate embeddings for a given model class.
     *
     * @param  class-string  $modelClass
     */
    protected function generateFor(string $modelClass, string $label): void
    {
        $models = $modelClass::all();
        $count = $models->count();

        if ($count === 0) {
            $this->warn("No {$label} found.");

            return;
        }

        $this->info("Generating embeddings for {$count} {$label}...");
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($models as $model) {
            try {
                $model->generateEmbedding();
            } catch (\Exception $e) {
                $this->error("\nFailed to generate embedding for {$label} ID {$model->id}: {$e->getMessage()}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }
}
