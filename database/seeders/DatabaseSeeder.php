<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole      = Role::findOrCreate('Admin', 'web');
        $teamMemberRole = Role::findOrCreate('Team Member', 'web');
        $viewerRole     = Role::findOrCreate('Viewer', 'web');

        $adminUser = User::updateOrCreate([
            'email' => 'tahmid.tf1@gmail.com',
        ], [
            'name'              => 'Tahmid Ferdous',
            'password'          => '12345678',
            'email_verified_at' => now(),
        ]);
        $adminUser->assignRole($adminRole);

        // $teamMemberUser = User::updateOrCreate([
        //     'email' => 'team@example.com',
        // ], [
        //     'name' => 'Team Member User',
        //     'password' => 'password',
        //     'email_verified_at' => now(),
        // ]);
        // $teamMemberUser->assignRole($teamMemberRole);

        // $viewerUser = User::updateOrCreate([
        //     'email' => 'viewer@example.com',
        // ], [
        //     'name' => 'Viewer User',
        //     'password' => 'password',
        //     'email_verified_at' => now(),
        // ]);
        // $viewerUser->assignRole($viewerRole);
    }
}
