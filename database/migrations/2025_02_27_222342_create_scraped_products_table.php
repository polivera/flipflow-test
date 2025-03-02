<?php

use App\Contexts\Crawler\Infrastructure\Persistence\Model\CrawledPagesModel;
use App\Contexts\Scraper\Infrastructure\Persistence\Model\ScrapedProductsModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(ScrapedProductsModel::TABLE_NAME, function (Blueprint $table) {
            $table->id(ScrapedProductsModel::ID);
            $table->bigInteger(ScrapedProductsModel::CRAWLED_PAGE_ID);
            $table->string(ScrapedProductsModel::NAME);
            $table->integer(ScrapedProductsModel::PRICE);
            $table->string(ScrapedProductsModel::CURRENCY_CODE);
            $table->string(ScrapedProductsModel::PRODUCT_URL);
            $table->string(ScrapedProductsModel::IMAGE_URL);
            $table->timestamps();

            $table->foreign(ScrapedProductsModel::CRAWLED_PAGE_ID)
                ->references(CrawledPagesModel::ID)
                ->on(CrawledPagesModel::TABLE_NAME);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(ScrapedProductsModel::TABLE_NAME);
    }
};
