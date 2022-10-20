<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('country_id')->unsigned();
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade');
            $table->char('country_code')->default(1);
            $table->string('fips_code')->default(NULL);
            $table->string('iso2')->default(NULL);
            $table->string('type')->default(NULL);
            $table->decimal('latitude')->default(NULL);
            $table->decimal('longitude')->default(NULL);
            $table->integer('flag')->default(1);
            $table->string('wikiDataId')->default(NULL);
            
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('states');
    }
}
