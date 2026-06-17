<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('applicant_experiences', function (Blueprint $table) {
            $table->string('from_month')->nullable()->after('from_year');
            $table->string('to_month')->nullable()->after('to_year');
        });
    }

    public function down()
    {
        Schema::table('applicant_experiences', function (Blueprint $table) {
            $table->dropColumn(['from_month', 'to_month']);
        });
    }
};
