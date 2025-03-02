<?php

declare(strict_types=1);

namespace App\Contexts\Product\Interface\Console;

use App\Contexts\Product\Application\Command\ListUrlProductsCommand;
use App\Contexts\Product\Application\Contract\ListUrlProductsAppServiceInterface;
use App\Contexts\Product\Interface\Dto\ProductListResponse;
use Illuminate\Console\Command;

final class GetProductList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:show-product-list {--url=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve content from page';


    public function __construct(
        private readonly ListUrlProductsAppServiceInterface $listUrlProductsAppService,
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

        $resultList = $this->listUrlProductsAppService->handle(ListUrlProductsCommand::create($url));
        $this->info(ProductListResponse::fromValueObject($resultList)->toPrettyJson());
    }
}
