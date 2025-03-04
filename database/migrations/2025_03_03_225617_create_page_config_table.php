<?php

use App\Contexts\Crawler\Infrastructure\Persistence\Model\PageConfigModel;
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
        Schema::create(PageConfigModel::TABLE_NAME, function (Blueprint $table) {
            $table->id(PageConfigModel::ID);
            $table->string(PageConfigModel::SITE)->index();
            $table->tinyInteger(PageConfigModel::PRIORITY);
            $table->json(PageConfigModel::HEADERS)->nullable();
            $table->json(PageConfigModel::COOKIES)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(PageConfigModel::TABLE_NAME);
    }
};
