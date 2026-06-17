<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('applicant_experiences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_id')->index();
            $table->string('organization');
            $table->string('role')->nullable();
            $table->string('from_year')->nullable();
            $table->string('to_year')->nullable();
            $table->text('details')->nullable();
            $table->timestamps();

            $table->foreign('applicant_id')->references('id')->on('applicants')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('applicant_experiences');
    }
};
