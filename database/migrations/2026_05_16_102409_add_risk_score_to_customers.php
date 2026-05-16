<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::table(
            'customers',
            function(Blueprint $table){

                $table->integer(
                    'risk_score'
                )
                ->default(0);

            }
        );
    }

    public function down()
    {
        Schema::table(
            'customers',
            function(Blueprint $table){

                $table->dropColumn(
                    'risk_score'
                );

            }
        );
    }

};