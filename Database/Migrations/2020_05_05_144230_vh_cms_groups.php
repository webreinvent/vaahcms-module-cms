<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VhCmsGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('vh_cms_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable();
            $table->uuid('uuid')->nullable();

            $table->integer('sort')->nullable();
            $table->integer('vh_cms_content_type_id')->nullable();
            $table->string('name', 100)->nullable();
            $table->string('slug', 100)->nullable();
            $table->string('excerpt')->nullable();
            $table->boolean('is_repeatable')->nullable();

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
        Schema::dropIfExists('vh_cms_groups');
    }
}
