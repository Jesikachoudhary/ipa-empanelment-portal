<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerificationFieldsToAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('registration_code')->nullable()->after('remember_token');
            $table->timestamp('registration_code_sent_at')->nullable()->after('registration_code');
            $table->timestamp('email_verified_at')->nullable()->after('registration_code_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['registration_code', 'registration_code_sent_at', 'email_verified_at']);
        });
    }
}
