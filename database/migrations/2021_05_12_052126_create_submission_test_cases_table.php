<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionTestCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submission_test_cases', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('submission_id')->references('id')->on('submissions')->onDelete('cascade');
            $table->foreignId('verdict_id')->references('id')->on('verdicts')->onDelete('cascade');

            $table->string('hash_file')->nullable();
            $table->text('input')->nullable();
            $table->text('output')->nullable();
            $table->text('expected_output')->nullable();

            $table->integer('time')->default(0);
            $table->integer('memory')->default(0);

            $table->text('checker_log')->nullable();
            $table->text('compiler_log')->nullable();

            $table->integer('point')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submission_test_cases');
    }
}
