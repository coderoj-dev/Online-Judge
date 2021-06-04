<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('problem_id')->references('id')->on('problems')->onDelete('cascade');
            $table->foreignId('verdict_id')->references('id')->on('verdicts')->onDelete('cascade');
            $table->foreignId('language_id')->references('id')->on('languages')->onDelete('cascade');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('type');
            $table->enum('judge_type', ['binary', 'partial'])->default('binary');

            $table->text('source_code')->nullable();

            $table->integer('time_limit');
            $table->integer('memory_limit');
            $table->integer('time');
            $table->integer('memory');

            $table->integer('run_on_test')->default(1);
            $table->integer('total_test_case')->default(0);

            $table->integer('total_point')->default(0);
            $table->integer('passed_point')->default(0);
            
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
        Schema::dropIfExists('submissions');
    }
}
