<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->char('iso3', 3)->default(NULL);
            $table->char('numeric_code', 3)->default(NULL);
            $table->char('iso2', 2)->default(NULL);
            $table->string('phonecode')->default(NULL);
            $table->string('capital')->default(NULL);
            $table->string('currency')->default(NULL);
            $table->string('currency_name')->default(NULL);
            $table->string('currency_symbol')->default(NULL);

            $table->string('tld')->default(NULL);
            $table->string('native')->default(NULL);
            $table->string('region')->default(NULL);;
            $table->string('subregion')->default(NULL);
            $table->text('timezones');
            $table->text('translations');
            $table->decimal('latitude')->default(NULL);
            $table->decimal('longitude')->default(NULL);

            $table->string('emoji')->default(NULL);
            $table->string('emojiU')->default(NULL);
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
        Schema::dropIfExists('countries');
    }
}
