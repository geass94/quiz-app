<?php

use App\Models\Quiz\Quiz;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('type')->default(Quiz::TYPES['BINARY'])->after('content');
            $table->index('type');
        });

        DB::statement('UPDATE questions q JOIN quizzes z ON q.quiz_id = z.id SET q.type = z.type');
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropColumn('type');
        });
    }
};
