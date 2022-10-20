<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtpColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('otp_attempts')->after('otp_pin')->default(3);
            $table->string('otp_resend_time')->after('otp_attempts')->nullable();
            $table->string('otp_time')->after('otp_resend_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('otp_attempts');
            $table->dropColumn('otp_resend_time');
            $table->dropColumn('otp_time');
            //
        });
    }
}
