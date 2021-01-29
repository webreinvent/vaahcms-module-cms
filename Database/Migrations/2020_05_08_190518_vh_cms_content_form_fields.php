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
            $table->uuid('uuid')->nullable()->index();

            $table->integer('vh_cms_content_id')->nullable()->index();
            $table->integer('vh_cms_form_group_id')->nullable()->index();
            $table->integer('vh_cms_form_field_id')->nullable()->index();
            $table->text('content')->nullable();

            $table->text('meta')->nullable();

            $table->integer('created_by')->nullable()->index();
            $table->integer('updated_by')->nullable()->index();
            $table->integer('deleted_by')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['created_at']);
            $table->index(['updated_at']);
            $table->index(['deleted_at']);

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
