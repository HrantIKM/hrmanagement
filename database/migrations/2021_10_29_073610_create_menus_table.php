<?php

use App\Models\Menu\Enums\MenuType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('slug', 50)->unique();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('group_name', 40)->nullable();
            $table->string('url', 100)->nullable();
            $table->string('icon', 50)->nullable();
            $table->enum('type', [MenuType::ADMIN, MenuType::PROFILE]);
            $table->unsignedTinyInteger('check_permission')->default(1)->nullable();
            $table->showStatus();
            $table->sortOrder();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
