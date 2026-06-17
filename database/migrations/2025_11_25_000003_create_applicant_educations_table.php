<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('applicant_educations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_id')->index();
            $table->string('qualification');
            $table->string('institution')->nullable();
            $table->string('year_of_passing')->nullable();
            $table->timestamps();

            $table->foreign('applicant_id')->references('id')->on('applicants')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('applicant_educations');
    }
};
