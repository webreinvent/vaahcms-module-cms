<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVhCmsMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vh_cms_menu_items', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('parent_id')->nullable()->index();

            $table->string('type')->nullable()->index();
            $table->string('name')->nullable()->index();
            $table->string('slug')->nullable()->index();
            $table->string('title')->nullable()->index();
            $table->string('attr_id')->nullable();
            $table->string('attr_class')->nullable();
            $table->boolean('attr_target_blank')->nullable();
            $table->integer('vh_menu_id')->nullable()->index();
            $table->integer('vh_content_id')->nullable()->index();
            $table->integer('sort')->nullable()->index();
            $table->boolean('is_home')->nullable()->index();
            $table->string('uri')->nullable()->index();
            $table->boolean('is_active')->nullable()->index();
            $table->string('vh_permission_slug')->nullable()->index();
            $table->json('meta')->nullable();

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
        Schema::dropIfExists('vh_cms_menu_items');
    }
}
