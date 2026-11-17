<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->unique()->after('id')->constrained()->nullOnDelete();
        });

        DB::table('members')->orderBy('id')->get()->each(function ($member) {
            $userId = DB::table('users')->where('email', $member->email)
                ->where('role', 'member')->value('id');

            if ($userId) {
                DB::table('members')->where('id', $member->id)->update(['user_id' => $userId]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
