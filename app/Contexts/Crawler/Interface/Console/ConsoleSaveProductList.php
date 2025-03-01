<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Interface\Console;

use App\Contexts\Crawler\Application\Command\GetUrlContentCommand;
use App\Contexts\Crawler\Application\Exception\GetUrlContentAppServiceException;
use App\Contexts\Crawler\Application\Service\GetUrlContentAppService;
use App\Contexts\Crawler\Interface\Dto\ScrapPageResultResponse;
use Illuminate\Console\Command;

final class ConsoleSaveProductList extends Command
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


    public function __construct(
        private readonly GetUrlContentAppService $getUrlContentAppService,
    )
    {
        parent::__construct();
    }

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
            $result = $this->getUrlContentAppService->handle(
                new GetUrlContentCommand($url)
            );
            $this->info(ScrapPageResultResponse::fromValueObject($url, $result)->toPrettyJson());
        } catch (GetUrlContentAppServiceException $e) {
            $this->error("Error processing content. Error: " . $e->getMessage());
        }
    }
}
