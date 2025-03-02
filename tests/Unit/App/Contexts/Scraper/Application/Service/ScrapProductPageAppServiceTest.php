<?php

declare(strict_types=1);

namespace Tests\Unit\App\Contexts\Scraper\Application\Service;

use App\Contexts\Scraper\Application\Command\ScrapProductPageCommand;
use App\Contexts\Scraper\Application\Exception\ScrapProductPageAppServiceException;
use App\Contexts\Scraper\Application\Service\ScrapProductPageAppService;
use App\Contexts\Scraper\Domain\Contract\ScrapProductPageServiceInterface;
use App\Contexts\Scraper\Domain\Exception\ScrapProductPageServiceException;
use App\Contexts\Scraper\Domain\ValueObject\ScrapPageResults;
use App\Shared\Domain\ValueObject\NumberID;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Tests\Stubs\Context\Scraper\ValueObject\ScrapPageResultsStub;

class ScrapProductPageAppServiceTest extends TestCase
{
    private ScrapProductPageServiceInterface|MockInterface $scrapProductPageServiceMock;
    private ScrapProductPageAppService $service;

    public function setUp(): void
    {
        $this->scrapProductPageServiceMock = Mockery::mock(ScrapProductPageServiceInterface::class);
        $this->service = new ScrapProductPageAppService($this->scrapProductPageServiceMock);
    }

    public function testIDNumberIsInvalid(): void
    {
        $this->expectException(ScrapProductPageAppServiceException::class);
        $this->expectExceptionMessage('Error con input arguments');
        $this->service->handle(ScrapProductPageCommand::create(-1));
    }

    public function testScrapProductServiceThrowsException(): void
    {
        $command = ScrapProductPageCommand::create(123);
        $this->scrapProductPageServiceMock
            ->shouldReceive('handle')
            ->once()
            ->withArgs(function (NumberID $id) use ($command) {
                $this->assertEquals($id->value, $command->pageId);
                return true;
            })
            ->andThrow(ScrapProductPageServiceException::class);

        $this->expectException(ScrapProductPageAppServiceException::class);
        $this->expectExceptionMessage("Error scrapping content for crawled page ID: " . $command->pageId);

        $this->service->handle($command);
    }

    public function testHappyPath(): void
    {
        $command = ScrapProductPageCommand::create(123);
        $handleResult = ScrapPageResultsStub::random();
        $this->scrapProductPageServiceMock
            ->shouldReceive('handle')
            ->once()
            ->withArgs(function (NumberID $id) use ($command) {
                $this->assertEquals($id->value, $command->pageId);
                return true;
            })
            ->andReturn($handleResult);

        $result = $this->service->handle($command);
        $this->assertEquals($handleResult, $result);
    }
}
