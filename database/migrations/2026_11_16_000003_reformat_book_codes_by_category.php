<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $books = DB::table('books')->orderBy('id')->get();

        // Free existing unique values first, including codes entered before this format.
        foreach ($books as $book) {
            DB::table('books')->where('id', $book->id)->update([
                'kode_buku' => 'TMP'.$book->id,
            ]);
        }

        $sequences = [];
        foreach ($books as $book) {
            $categoryName = DB::table('categories')->where('id', $book->category_id)->value('nama_kategori') ?? 'N';
            $prefix = strtoupper(substr(trim($categoryName), 0, 1));
            $sequences[$prefix] = ($sequences[$prefix] ?? 0) + 1;

            DB::table('books')->where('id', $book->id)->update([
                'kode_buku' => $prefix.str_pad((string) $sequences[$prefix], 3, '0', STR_PAD_LEFT),
            ]);
        }
    }

    public function down(): void
    {
        // Kode buku yang sudah dipakai pada transaksi tidak dapat dipulihkan secara aman.
    }
};
