<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VhCmsFormFields extends Migration
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
            $table->uuid('uuid')->nullable()->index();
            $table->integer('vh_cms_field_type_id')->nullable()->index();
            $table->integer('vh_cms_form_group_id')->nullable()->index();
            $table->integer('sort')->nullable()->index();
            $table->string('name', 100)->nullable()->index();
            $table->string('slug', 100)->nullable()->index();
            $table->string('excerpt')->nullable();
            $table->boolean('is_searchable')->nullable()->index();
            $table->boolean('is_repeatable')->nullable()->index();

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
        Schema::dropIfExists('vh_cms_form_fields');
    }
}
