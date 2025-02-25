<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Interfaces\Console;

use Illuminate\Console\Command;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Http;

final class SaveProductList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-product-list {--url=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve content from page';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $url = $this->option('url');
        if (empty($url)) {
            $this->error('Please provide a URL');
            exit(1);
        }

        try {
            // Enable JavaScript and cookies to continue
            $cookieJar = new CookieJar();
            $response = Http::withOptions([
                'cookies' => $cookieJar,
            ])->withHeaders(
                [
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
                    'Accept-Encoding' => 'gzip, deflate, br, zstd',
                    'Accept-Language' => 'en-US,en;q=0.9',
                    'Cache-Control' => 'no-cache',
                    'Connection' => 'keep-alive',
                    'Pragma' => 'no-cache',
                    'Sec-Fetch-Dest' => 'document',
                    'Sec-Fetch-Mode' => 'navigate',
                    'Sec-Fetch-Site' => 'none',
                    'Sec-Fetch-User' => '?1',
                    'Upgrade-Insecure-Requests' => '1',
                ]
            )->get($url);
            if ($response->successful()) {
                var_dump($response->body());
                return;
            }
            var_dump($response->body());
            var_dump("Request failed");
            exit(1);
        } catch (\Throwable $e) {
            var_dump($e->getMessage());
            exit(1);
        }
    }
}
