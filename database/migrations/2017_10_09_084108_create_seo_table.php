<?php

use Despark\Cms\Models\IgniMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeoTable extends IgniMigration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create($this->getTableName('seo'), function (Blueprint $table) {
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
        Schema::dropIfExists($this->getTableName('seo'));
    }
}
