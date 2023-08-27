<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ProductCategoryName');
            $table->string('ProductSeriesName');
            $table->string('ProductModel');
            $table->integer('Stock');
            $table->integer('Price');
            $table->text('ImageUrl');
            $table->string('Cpu');
            $table->string('Memory');
            $table->string('IntegratedGfx');
            $table->string('Storage');
            $table->float('ScreenSize');
            $table->string('Resolution');
            $table->string('RefreshRate');
            $table->string('Color');
            $table->string('Battery');
            $table->string('OperatingSystem');
            $table->string('Package');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
