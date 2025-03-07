<?php

use App\Contexts\Crawler\Infrastructure\Persistence\Model\PageConfigModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table(PageConfigModel::TABLE_NAME)->insert([
            PageConfigModel::SITE => PageConfigModel::DEFAULT_CONFIG_SITE,
            PageConfigModel::PRIORITY => 1,
            PageConfigModel::HEADERS => json_encode($this->buildDefaultHeaders()),
            PageConfigModel::COOKIES => PageConfigModel::EMPTY_COOKIES_JSON
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table(PageConfigModel::TABLE_NAME)
            ->where(PageConfigModel::SITE, '=', PageConfigModel::DEFAULT_CONFIG_SITE)
            ->delete();
    }

    private function buildDefaultHeaders(): array
    {
        return [
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
            'Accept-Encoding' => 'gzip, deflate, br, zstd',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'Pragma' => 'no-cache',
            'Priority' => 'u=0, i',
            'Sec-Ch-Ua' => '"Not(A:Brand";v="99", "Brave";v="133", "Chromium";v="133"',
            'Sec-Ch-Ua-Mobile' => '?0',
            'Sec-Ch-Ua-Platform' => '"Linux"',
            'Sec-Fetch-Dest' => 'document',
            'Sec-Fetch-Mode' => 'navigate',
            'Sec-Fetch-Site' => 'none',
            'Sec-Fetch-User' => '?1',
            'Upgrade-Insecure-Requests' => '1',
        ];
    }
};
