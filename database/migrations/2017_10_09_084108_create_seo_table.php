<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateSeoTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $tablePrefix = config('ignicms.igniTablesPrefix');
        $tableName = $tablePrefix ? $tablePrefix . '_seo' : 'seo';
        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('resource_id');
            $table->string('resource_model');
            $table->string('meta_title')->nullable()->default(null);
            $table->string('meta_description')->nullable()->default(null);
            $table->string('facebook_title')->nullable()->default(null);
            $table->string('facebook_description')->nullable()->default(null);
            $table->string('twitter_title')->nullable()->default(null);
            $table->string('twitter_description')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $tablePrefix = config('ignicms.igniTablesPrefix');
        $tableName = $tablePrefix ? $tablePrefix . '_seo' : 'seo';
        Schema::dropIfExists($tableName);
    }
}
