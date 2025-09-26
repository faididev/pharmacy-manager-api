<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearApiCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:clear-cache {--type=all : Type of cache to clear (products, categories, all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear API cache for products and categories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');

        if ($type === 'all' || $type === 'products') {
            $this->clearProductsCache();
            $this->info('Products cache cleared successfully.');
        }

        if ($type === 'all' || $type === 'categories') {
            $this->clearCategoriesCache();
            $this->info('Categories cache cleared successfully.');
        }

        if ($type === 'all') {
            $this->info('All API cache cleared successfully.');
        }
    }

    private function clearProductsCache()
    {
        $keys = Cache::get('products_cache_keys', []);
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        Cache::forget('products_cache_keys');
    }

    private function clearCategoriesCache()
    {
        $keys = Cache::get('categories_cache_keys', []);
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        Cache::forget('categories_cache_keys');
    }
}

