<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalBankOrgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goal_bank_orgs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goal_id')->constrained();
            $table->integer('version')->nullable();
            $table->string('organization');
            $table->string('level1_program');
            $table->string('level2_division');
            $table->string('level3_branch');
            $table->string('level4');
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
        Schema::dropIfExists('goal_bank_orgs');
    }
}
