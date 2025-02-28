<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Interface\Console;

use App\Contexts\Crawler\Application\Command\GetUrlContentCommand;
use App\Contexts\Crawler\Application\Exception\GetUrlContentAppServiceException;
use App\Contexts\Crawler\Application\Service\GetUrlContentAppService;
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
            $this->getUrlContentAppService->handle(
                new GetUrlContentCommand($url)
            );
            $this->info('Content has been saved');
        } catch (GetUrlContentAppServiceException $e) {
            $this->error("Error processing content. Error: " . $e->getMessage());
        }
    }
}
