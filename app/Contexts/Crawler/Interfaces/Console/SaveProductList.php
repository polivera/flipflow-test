<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Interfaces\Console;

use App\Contexts\Crawler\Application\Command\GetUrlContentCommand;
use App\Contexts\Crawler\Application\Service\GetUrlContentAppService;
use App\Contexts\Crawler\Domain\ValueObject\Url;
use Illuminate\Console\Command;

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


    public function __construct(
        private GetUrlContentAppService $getUrlContentAppService,
    ) {
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
        $this->getUrlContentAppService->handle(
            new GetUrlContentCommand($url)
        );
    }
}
