<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VhCmsContentFormFieldsAddVhCmsFormGroupIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('vh_cms_content_form_fields', function (Blueprint $table) {
            $table->tinyInteger('vh_cms_form_group_index')
                ->after('vh_cms_form_field_id')
                ->default(0)
                ->nullable()
                ->index();
        });
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::table('vh_cms_content_form_fields', function (Blueprint $table) {
            $table->dropColumn('vh_cms_form_group_index');
        });
    }
}
