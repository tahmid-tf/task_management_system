<?php
namespace Database\Seeders;

use App\Models\AppSetting;
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
            'phone'             => '01700000000',
            'address'           => 'Dhaka, Bangladesh',
            'status'            => 'active',
            'password'          => '12345678',
            'email_verified_at' => now(),
        ]);
        $adminUser->assignRole($adminRole);

        AppSetting::setMailSystemEnabled(true);

        $defaultCategories = [
            ['name' => 'General', 'slug' => 'general', 'color' => '#0d6efd', 'position' => 1],
            ['name' => 'Development', 'slug' => 'development', 'color' => '#198754', 'position' => 2],
            ['name' => 'Operations', 'slug' => 'operations', 'color' => '#fd7e14', 'position' => 3],
        ];

        foreach ($defaultCategories as $category) {
            \App\Models\TaskCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

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
