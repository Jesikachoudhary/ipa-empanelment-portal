<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->decimal('years_of_experience', 5, 2)->nullable()->after('domain_knowledge');
        });
    }

    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn('years_of_experience');
        });
    }
};
