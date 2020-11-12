<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VhCmsFormGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('vh_cms_form_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable();
            $table->uuid('uuid')->nullable();

            $table->integer('sort')->nullable();
            $table->integer('groupable_id')->nullable();
            $table->string('groupable_type')->nullable();
            $table->string('name', 100)->nullable()->index();
            $table->string('slug', 100)->nullable()->index();
            $table->string('excerpt')->nullable();
            $table->boolean('is_repeatable')->nullable()->index();

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
        Schema::dropIfExists('vh_cms_form_groups');
    }
}
