<?php

use App\Models\Quiz\Quiz;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_quizzes', function (Blueprint $table) {
            $table->dropForeign(['quiz_id']);
            $table->dropColumn('quiz_id');

            $table->string('mode')->default(Quiz::TYPES['BINARY'])->after('user_id');
            $table->unsignedInteger('total_questions')->default(Quiz::SESSION_SIZE)->after('time_left');
            $table->unsignedInteger('unanswered_count')->default(0)->after('total_questions');
            $table->timestamp('submitted_at')->nullable()->after('unanswered_count');
        });
    }

    public function down()
    {
        Schema::table('user_quizzes', function (Blueprint $table) {
            $table->dropColumn(['mode', 'total_questions', 'unanswered_count', 'submitted_at']);
            $table->unsignedBigInteger('quiz_id')->nullable()->after('id');
            $table->foreign('quiz_id')->references('id')->on('quizzes')->cascadeOnDelete();
        });
    }
};
