<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_quizzes', function (Blueprint $table) {
            $table->json('question_ids')->nullable()->after('total_questions');
        });
    }

    public function down()
    {
        Schema::table('user_quizzes', function (Blueprint $table) {
            $table->dropColumn('question_ids');
        });
    }
};
