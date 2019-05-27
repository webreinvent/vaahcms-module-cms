<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVhCmsContentsTable extends Migration
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

            $table->integer('contentable_id')->nullable();
            $table->string('contentable_type')->nullable();

            $table->integer('table_id')->nullable();

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
        Schema::dropIfExists('vh_cms_contents');
    }
}
