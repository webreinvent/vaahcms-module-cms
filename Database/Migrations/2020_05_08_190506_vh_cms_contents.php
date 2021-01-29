<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VhCmsContents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('vh_cms_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->nullable()->index();

            $table->integer('parent_id')->nullable()->index();
            $table->integer('vh_cms_content_type_id')->nullable()->index();
            $table->integer('vh_theme_id')->nullable()->index();
            $table->integer('vh_theme_template_id')->nullable()->index();
            $table->string('name')->nullable()->index();
            $table->string('slug')->nullable()->index();
            $table->string('permalink')->nullable()->index();
            $table->integer('author')->nullable()->index();
            $table->dateTime('is_published_at')->nullable()->index();
            $table->string('status')->nullable()->index();
            $table->integer('total_comments')->nullable();

            $table->text('meta')->nullable();

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
        Schema::dropIfExists('vh_cms_contents');
    }
}
