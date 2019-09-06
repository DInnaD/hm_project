<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeigenKeysToUserVacancies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_vacancies', function (Blueprint $table) {
            $table->foreign('vacancy_id')->references('id')->on('vacancies')->onUpdate('RESTRICT')->onDelete('RESTRICT');            
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_vacancies', function (Blueprint $table) {
            $table->dropForeign('user_vacancies_vacancy_id_foreign');            
            $table->dropForeign('user_vacancies_user_id_foreign');
        });
    }
}
