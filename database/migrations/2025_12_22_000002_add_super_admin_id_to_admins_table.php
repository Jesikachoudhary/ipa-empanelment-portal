<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            if (!Schema::hasColumn('admins', 'super_admin_id')) {
                $table->unsignedBigInteger('super_admin_id')->nullable()->after('is_super');
                $table->foreign('super_admin_id')->references('id')->on('admins')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            if (Schema::hasColumn('admins', 'super_admin_id')) {
                $table->dropForeign(['super_admin_id']);
                $table->dropColumn('super_admin_id');
            }
        });
    }
};
