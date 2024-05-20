<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable()->unique();
            $table->foreignId('organization_id');
            $table->bigInteger('sequrence')->unsigned()->nullable();
            $table->string('category_code', 10);
            $table->string('title');
            $table->text('intro')->nullable();
            $table->text('content')->nullable();
            $table->date('valid_at')->nullable();
            $table->date('expire_at')->nullable();
            $table->text('url')->nullable();
            $table->string('reference')->nullable();
            $table->text('author')->nullable();
            $table->string('tags')->default();
            $table->char('lang', 2)->default('zh');
            $table->string('thumbnail')->nullable();
            $table->foreignId('user_id');
            $table->boolean('public');
            $table->boolean('published');
            $table->integer('sequence');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
