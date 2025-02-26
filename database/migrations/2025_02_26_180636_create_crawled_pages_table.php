<?php

use App\Contexts\Crawler\Infrastructure\Persistence\Model\CrawledPagesModel;
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
        Schema::create(CrawledPagesModel::TABLE_NAME, function (Blueprint $table) {
            $table->id(CrawledPagesModel::ID);
            $table->string(CrawledPagesModel::URL)->unique()->index();
            $table->text(CrawledPagesModel::CONTENT);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(CrawledPagesModel::TABLE_NAME);
    }
};
