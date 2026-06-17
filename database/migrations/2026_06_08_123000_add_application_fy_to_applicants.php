<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration {
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('application_fy', 9)->nullable()->after('applicant_type');
            $table->index('application_fy');
        });

        // Backfill existing applicants based on created_at (financial year: Apr 1 - Mar 31)
        $applicants = DB::table('applicants')->whereNull('application_fy')->get(['id', 'created_at']);
        foreach ($applicants as $a) {
            $dt = $a->created_at ? Carbon::parse($a->created_at) : Carbon::now();
            $fy = ($dt->month >= 4)
                ? $dt->year . '-' . ($dt->year + 1)
                : ($dt->year - 1) . '-' . $dt->year;
            DB::table('applicants')->where('id', $a->id)->update(['application_fy' => $fy]);
        }
    }

    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropIndex(['application_fy']);
            $table->dropColumn('application_fy');
        });
    }
};
