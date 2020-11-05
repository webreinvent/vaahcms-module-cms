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
            $table->uuid('uuid')->nullable();

            $table->integer('parent_id')->nullable();
            $table->integer('vh_cms_content_type_id')->nullable();
            $table->integer('vh_theme_id')->nullable();
            $table->integer('vh_theme_template_id')->nullable();
            $table->string('name')->nullable()->index();
            $table->string('slug')->nullable()->index();
            $table->dateTime('is_published_at')->nullable()->index();
            $table->string('status')->nullable()->index();
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
        Schema::dropIfExists('vh_cms_contents');
    }
}
