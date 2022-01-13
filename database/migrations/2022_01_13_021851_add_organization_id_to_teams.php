<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganizationIdToTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable();
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->foreignId('team_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('teams', function (Blueprint $table) {
        //     Schema::dropIfExists('teams');
        // });

        // Schema::table('organizations', function (Blueprint $table) {
        //     Schema::dropIfExists('organizations');
        // });
    }
}
