<?php

use App\Models\Article\Article;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleMlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_mls', function (Blueprint $table) {
            $table->foreignIdFor(Article::class)->constrained()->cascadeOnDelete();
            $table->char('lng_code', 2);
            $table->string('title', 100)->nullable();
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->metaData();
            $table->primary(['article_id', 'lng_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_mls');
    }
}
