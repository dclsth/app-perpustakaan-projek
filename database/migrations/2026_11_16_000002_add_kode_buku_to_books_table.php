<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('kode_buku', 30)->nullable()->unique()->after('id');
        });

        $sequences = [];
        DB::table('books')->orderBy('id')->get()->each(function ($book) use (&$sequences) {
            $categoryName = DB::table('categories')->where('id', $book->category_id)->value('nama_kategori') ?? 'N';
            $prefix = strtoupper(substr(trim($categoryName), 0, 1));
            $sequences[$prefix] = ($sequences[$prefix] ?? 0) + 1;

            DB::table('books')->where('id', $book->id)->update([
                'kode_buku' => $prefix.str_pad((string) $sequences[$prefix], 3, '0', STR_PAD_LEFT),
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropUnique(['kode_buku']);
            $table->dropColumn('kode_buku');
        });
    }
};
