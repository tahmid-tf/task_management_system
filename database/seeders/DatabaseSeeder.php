<?php
namespace Database\Seeders;

use App\Models\AppSetting;
use App\Models\Task;
use App\Models\TaskActivityLog;
use App\Models\TaskCategory;
use App\Models\TaskComment;
use App\Models\TaskLabel;
use App\Models\User;
use Carbon\Carbon;
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

        $teamMemberUser = User::updateOrCreate([
            'email' => 'team.member@example.com',
        ], [
            'name'              => 'Nadia Rahman',
            'phone'             => '01800000000',
            'address'           => 'Chattogram, Bangladesh',
            'status'            => 'active',
            'password'          => '12345678',
            'email_verified_at' => now(),
        ]);
        $teamMemberUser->assignRole($teamMemberRole);

        AppSetting::setMailSystemEnabled(true);

        $defaultCategories = [
            ['name' => 'General', 'slug' => 'general', 'color' => '#0d6efd', 'position' => 1],
            ['name' => 'Development', 'slug' => 'development', 'color' => '#198754', 'position' => 2],
            ['name' => 'Operations', 'slug' => 'operations', 'color' => '#fd7e14', 'position' => 3],
        ];

        foreach ($defaultCategories as $category) {
            TaskCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $labels = collect([
            ['name' => 'Urgent', 'color' => '#dc2626'],
            ['name' => 'Frontend', 'color' => '#2563eb'],
            ['name' => 'Backend', 'color' => '#16a34a'],
            ['name' => 'Documentation', 'color' => '#7c3aed'],
        ])->map(function (array $label) {
            return TaskLabel::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($label['name'])],
                $label
            );
        });

        $generalCategory = TaskCategory::where('slug', 'general')->first();
        $developmentCategory = TaskCategory::where('slug', 'development')->first();
        $operationsCategory = TaskCategory::where('slug', 'operations')->first();

        $seedTasks = [
            [
                'title' => 'Prepare weekly dashboard summary',
                'description' => 'Collect task activity, highlight blockers, and prepare the weekly status snapshot for leadership.',
                'task_category_id' => $generalCategory?->id,
                'assigned_to' => $teamMemberUser->id,
                'status' => 'todo',
                'priority' => 'medium',
                'due_date' => Carbon::today()->addDays(3),
                'estimated_time' => 4,
                'actual_time' => 1,
                'labels' => ['Urgent', 'Documentation'],
                'comment' => 'Please include overdue items and the completion trend.',
                'activity' => 'Task prepared for weekly reporting.',
            ],
            [
                'title' => 'Review login flow and inactive account handling',
                'description' => 'Validate the login validation path, inactive redirects, and status-change email behavior.',
                'task_category_id' => $developmentCategory?->id,
                'assigned_to' => $adminUser->id,
                'status' => 'in_progress',
                'priority' => 'high',
                'due_date' => Carbon::today()->subDay(),
                'estimated_time' => 6,
                'actual_time' => 2.5,
                'labels' => ['Backend'],
                'comment' => 'The inactive-account experience should stay clear and immediate.',
                'activity' => 'Review started for authentication flow.',
            ],
            [
                'title' => 'Resolve export formatting feedback',
                'description' => 'Fine-tune spreadsheet styling, priority colors, and autosized columns for stakeholder review.',
                'task_category_id' => $operationsCategory?->id,
                'assigned_to' => $teamMemberUser->id,
                'status' => 'done',
                'priority' => 'low',
                'due_date' => Carbon::today()->subDays(2),
                'estimated_time' => 3,
                'actual_time' => 2,
                'labels' => ['Frontend', 'Documentation'],
                'comment' => 'Export formatting is ready for final sign-off.',
                'activity' => 'Export feedback task completed.',
            ],
            [
                'title' => 'Audit overdue tasks and send reminders',
                'description' => 'Review overdue work, confirm assignees, and trigger delayed task reminders for active users.',
                'task_category_id' => $operationsCategory?->id,
                'assigned_to' => $teamMemberUser->id,
                'status' => 'backlog',
                'priority' => 'critical',
                'due_date' => Carbon::today()->subDays(4),
                'estimated_time' => 5,
                'actual_time' => 0,
                'labels' => ['Urgent', 'Backend'],
                'comment' => 'This one should be highlighted in the Alert Center.',
                'activity' => 'Overdue task queued for reminder workflow.',
            ],
        ];

        foreach ($seedTasks as $seedTask) {
            $task = Task::updateOrCreate(
                ['title' => $seedTask['title']],
                [
                    'task_category_id' => $seedTask['task_category_id'],
                    'created_by'       => $adminUser->id,
                    'assigned_to'      => $seedTask['assigned_to'],
                    'assigned_at'      => now(),
                    'description'      => $seedTask['description'],
                    'status'           => $seedTask['status'],
                    'priority'         => $seedTask['priority'],
                    'due_date'         => $seedTask['due_date'],
                    'estimated_time'   => $seedTask['estimated_time'],
                    'actual_time'      => $seedTask['actual_time'],
                    'position'         => 1,
                    'archived_at'      => null,
                ]
            );

            $task->labels()->sync(
                $labels
                    ->filter(fn (TaskLabel $label) => in_array($label->name, $seedTask['labels'], true))
                    ->pluck('id')
                    ->values()
                    ->all()
            );

            TaskComment::updateOrCreate(
                [
                    'task_id' => $task->id,
                    'user_id' => $adminUser->id,
                ],
                [
                    'body' => $seedTask['comment'],
                ]
            );

            TaskActivityLog::updateOrCreate(
                [
                    'task_id' => $task->id,
                    'action'  => 'seeded',
                ],
                [
                    'user_id'     => $adminUser->id,
                    'description' => $seedTask['activity'],
                    'properties'  => [
                        'seeded' => true,
                    ],
                ]
            );
        }
    }
}
