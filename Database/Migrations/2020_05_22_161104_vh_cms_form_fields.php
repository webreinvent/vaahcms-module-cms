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
            $table->uuid('uuid')->nullable();
            $table->integer('vh_cms_field_type_id')->nullable();
            $table->integer('vh_cms_form_group_id')->nullable();
            $table->integer('sort')->nullable();
            $table->string('name', 100)->nullable();
            $table->string('slug', 100)->nullable();
            $table->string('excerpt')->nullable();
            $table->boolean('is_searchable')->nullable();
            $table->boolean('is_repeatable')->nullable();

            $table->text('meta')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();  

            $table->index(['name', 'slug', 'is_repeatable', 'is_searchable']);
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
