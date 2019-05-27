<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVhCmsPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vh_cms_pages', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('vh_theme_template_id')->nullable();
            $table->integer('vh_cms_page_id')->nullable();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->string('slug')->nullable();
            $table->string('attr_id')->nullable();
            $table->string('attr_class')->nullable();
            $table->string('status')->nullable();
            $table->string('visibility')->nullable();
            $table->text('meta')->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vh_cms_pages');
    }
}
