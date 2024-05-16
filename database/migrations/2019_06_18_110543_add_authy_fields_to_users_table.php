<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthyFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('authy_enabled')->default(false)->after('social_avatar');
            $table->string('authy_status', 60)->nullable()->default('unverified')->after('authy_enabled');
            $table->string('authy_id', 60)->nullable()->after('authy_status');
            $table->string('country_code', 10)->nullable()->after('authy_id');
            $table->string('authy_email', 110)->nullable()->after('country_code');
            $table->string('authy_phone', 60)->nullable()->after('authy_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('authy_enabled');
            $table->dropColumn('authy_status');
            $table->dropColumn('authy_id');
            $table->dropColumn('country_code');
            $table->dropColumn('authy_email');
            $table->dropColumn('authy_phone');
        });
    }
}
