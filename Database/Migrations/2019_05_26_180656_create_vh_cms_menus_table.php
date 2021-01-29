<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVhCmsMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vh_cms_menus', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable()->index();
            $table->string('slug')->nullable()->index();
            $table->string('attr_id')->nullable();
            $table->string('attr_class')->nullable();
            $table->integer('vh_theme_location_id')->nullable()->index();
            $table->string('vh_permission_slug')->nullable()->index();

            $table->integer('created_by')->nullable()->index();
            $table->integer('updated_by')->nullable()->index();
            $table->integer('deleted_by')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['created_at', 'updated_at', 'deleted_at']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vh_cms_menus');
    }
}
