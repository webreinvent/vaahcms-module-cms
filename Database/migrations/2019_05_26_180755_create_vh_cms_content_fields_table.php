<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVhCmsContentFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vh_cms_content_fields', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('vh_page_id')->nullable();
            $table->integer('vh_form_group_id')->nullable();
            $table->integer('vh_form_group_order')->nullable();
            $table->integer('vh_form_field_id')->nullable();
            $table->integer('vh_form_field_order')->nullable();
            $table->string('vh_form_field_type')->nullable();
            $table->longText('content')->nullable();
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
        Schema::dropIfExists('vh_cms_content_fields');
    }
}
