<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VhCmsContentTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('vh_cms_content_types', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->nullable();

            $table->string('name', 60)->nullable()->index();
            $table->string('slug', 60)->nullable()->index();
            $table->string('plural', 60)->nullable()->index();
            $table->string('plural_slug', 60)->nullable()->index();
            $table->string('singular', 60)->nullable()->index();
            $table->string('singular_slug', 60)->nullable()->index();
            $table->string('excerpt')->nullable();
            $table->boolean('is_published')->nullable()->index();
            $table->boolean('is_commentable')->nullable();
            $table->json('content_statuses')->nullable();
            $table->integer('total_records')->nullable();
            $table->integer('published_records')->nullable();
            $table->integer('total_comments')->nullable();

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
        Schema::dropIfExists('vh_cms_content_types');
    }
}
