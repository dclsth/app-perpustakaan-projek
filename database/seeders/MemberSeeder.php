<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        User::where('role', 'member')->get()->each(function (User $user) {
            Member::updateOrCreate(
                ['email' => $user->email],
                [
                    'user_id' => $user->id,
                    'nama' => $user->name,
                    'nim' => 'REG-'.$user->id,
                    'nomor_telepon' => '08000000000',
                    'alamat' => 'Belum diisi',
                    'status' => 'aktif',
                ]
            );
        });

        Member::factory()->count(15)->create();
    }
}
