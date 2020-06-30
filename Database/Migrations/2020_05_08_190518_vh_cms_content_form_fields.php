<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VhCmsContentFormFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('vh_cms_content_form_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->nullable();

            $table->integer('vh_cms_content_id')->nullable();
            $table->integer('vh_cms_form_group_id')->nullable();
            $table->integer('vh_cms_form_field_id')->nullable();
            $table->text('content')->nullable();

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
        Schema::dropIfExists('vh_cms_content_form_fields');
    }
}
