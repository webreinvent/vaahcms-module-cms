<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVhCmsFormFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vh_cms_form_fields', function (Blueprint $table) {
            $table->increments('id');


            $table->integer('vh_cms_form_group_id')->nullable();

            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('excerpt')->nullable();
            $table->string('type')->nullable();
            $table->integer('order')->nullable();
            $table->boolean('is_repeatable')->nullable();

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
        Schema::dropIfExists('vh_cms_form_fields');
    }
}
