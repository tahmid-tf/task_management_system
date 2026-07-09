<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskActivityLog;
use App\Models\TaskAttachment;
use App\Models\TaskCategory;
use App\Models\TaskComment;
use App\Models\TaskLabel;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    private const STATUSES = [
        'backlog'     => 'Backlog',
        'todo'        => 'To Do',
        'in_progress' => 'In Progress',
        'review'      => 'Review',
        'testing'     => 'Testing',
        'done'        => 'Done',
        'blocked'     => 'Blocked',
        'cancelled'   => 'Cancelled',
    ];

    private const PRIORITIES = [
        'low'      => 'Low',
        'medium'   => 'Medium',
        'high'     => 'High',
        'critical' => 'Critical',
    ];

    public function index(Request $request): View
    {
        $categories = TaskCategory::query()
            ->withCount([
                'tasks as open_tasks_count' => fn ($query) => $query->whereNull('archived_at')->whereNull('deleted_at'),
            ])
            ->orderBy('position')
            ->orderBy('name')
            ->get();

        $selectedCategory = $this->resolveSelectedCategory($request, $categories);
        $boardTasks = $this->boardTasks($selectedCategory?->id, $request->string('q')->toString());

        return view('admin.tasks.board', [
            'categories'        => $categories,
            'selectedCategory'   => $selectedCategory,
            'boardTasks'         => $boardTasks,
            'statuses'           => self::STATUSES,
            'priorities'         => self::PRIORITIES,
            'users'              => User::query()->orderBy('name')->get(['id', 'name', 'email', 'image']),
            'labels'             => TaskLabel::query()->orderBy('name')->get(['id', 'name', 'slug', 'color']),
            'summaryCounts'      => $this->summaryCounts($boardTasks),
            'querySearch'        => $request->string('q')->toString(),
        ]);
    }

    public function table(Request $request): View
    {
        $tasks = Task::query()
            ->with(['category', 'creator', 'assignee', 'labels'])
            ->whereNull('archived_at')
            ->latest()
            ->get();

        return view('admin.tasks.table', [
            'tasks'      => $tasks,
            'statuses'   => self::STATUSES,
            'priorities' => self::PRIORITIES,
        ]);
    }

    public function archived(Request $request): View
    {
        $tasks = Task::query()
            ->with(['category', 'creator', 'assignee', 'labels'])
            ->whereNotNull('archived_at')
            ->orderByDesc('archived_at')
            ->get();

        return view('admin.tasks.archived', [
            'tasks' => $tasks,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.tasks.create', $this->formPayload([
            'task' => null,
        ]));
    }

    public function store(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $validated = $this->validateTask($request);

        $task = DB::transaction(function () use ($validated, $request) {
            $task = Task::create([
                'task_category_id' => $validated['task_category_id'],
                'created_by'       => $request->user()->id,
                'assigned_to'      => $validated['assigned_to'] ?? null,
                'title'            => $validated['title'],
                'description'      => $validated['description'] ?? null,
                'status'           => $validated['status'],
                'priority'         => $validated['priority'],
                'due_date'         => $validated['due_date'] ?? null,
                'estimated_time'   => $validated['estimated_time'] ?? null,
                'actual_time'      => $validated['actual_time'] ?? null,
                'position'         => $this->nextPosition($validated['task_category_id'], $validated['status']),
            ]);

            $this->syncLabels($task, $request->string('labels')->toString());
            $this->storeAttachments($task, $request);
            $this->logActivity($task, 'created', 'Task created.');

            return $task->load(['category', 'creator', 'assignee', 'labels']);
        });

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task created successfully.',
                'task'    => $this->taskPayload($task),
            ]);
        }

        return redirect()
            ->route('admin.tasks.board')
            ->with('success', 'Task created successfully.');
    }

    public function edit(Task $task): View
    {
        return view('admin.tasks.edit', $this->formPayload([
            'task' => $task->load(['labels', 'attachments']),
        ]));
    }

    public function update(Request $request, Task $task): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $validated = $this->validateTask($request, $task->id);

        DB::transaction(function () use ($validated, $request, $task) {
            $task->update([
                'task_category_id' => $validated['task_category_id'],
                'assigned_to'      => $validated['assigned_to'] ?? null,
                'title'            => $validated['title'],
                'description'      => $validated['description'] ?? null,
                'status'           => $validated['status'],
                'priority'         => $validated['priority'],
                'due_date'         => $validated['due_date'] ?? null,
                'estimated_time'   => $validated['estimated_time'] ?? null,
                'actual_time'      => $validated['actual_time'] ?? null,
            ]);

            $this->syncLabels($task, $request->string('labels')->toString());
            $this->storeAttachments($task, $request);
            $this->logActivity($task, 'updated', 'Task updated.');
        });

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully.',
            ]);
        }

        return redirect()
            ->route('admin.tasks.board', [
                'category' => TaskCategory::find($validated['task_category_id'])?->slug,
            ])
            ->with('success', 'Task updated successfully.');
    }

    public function details(Task $task): JsonResponse
    {
        $task->load([
            'category',
            'creator',
            'assignee',
            'labels',
            'comments.user',
            'attachments.user',
            'activityLogs.user',
        ]);

        return response()->json([
            'success' => true,
            'task' => $this->taskPayload($task),
        ]);
    }

    public function move(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'status'     => ['required', Rule::in(array_keys(self::STATUSES))],
            'position'   => ['required', 'integer', 'min:0'],
            'category_id'=> ['nullable', 'integer', Rule::exists('task_categories', 'id')],
        ]);

        $task->update([
            'status'           => $validated['status'],
            'position'         => $validated['position'],
            'task_category_id' => $validated['category_id'] ?? $task->task_category_id,
        ]);

        $this->logActivity($task, 'moved', 'Task moved on the board.', $validated);

        return response()->json([
            'success' => true,
            'message' => 'Task moved successfully.',
        ]);
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id'   => ['required', 'integer', Rule::exists('task_categories', 'id')],
            'columns'       => ['required', 'array'],
            'columns.*'     => ['array'],
            'columns.*.*'   => ['integer', Rule::exists('tasks', 'id')],
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['columns'] as $status => $ids) {
                foreach ($ids as $index => $id) {
                    Task::whereKey($id)->update([
                        'status'   => $status,
                        'position' => $index + 1,
                    ]);
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Board order saved successfully.',
        ]);
    }

    public function archive(Task $task): JsonResponse
    {
        $task->update([
            'archived_at' => now(),
        ]);

        $this->logActivity($task, 'archived', 'Task archived.');

        return response()->json([
            'success' => true,
            'message' => 'Task archived successfully.',
            'archived' => true,
        ]);
    }

    public function unarchive(Task $task): JsonResponse
    {
        $task->update([
            'archived_at' => null,
        ]);

        $this->logActivity($task, 'unarchived', 'Task restored from archive.');

        return response()->json([
            'success' => true,
            'message' => 'Task restored successfully.',
            'archived' => false,
        ]);
    }

    public function duplicate(Task $task): JsonResponse
    {
        $copy = DB::transaction(function () use ($task) {
            $task->loadMissing('labels');

            $copy = Task::create([
                'task_category_id' => $task->task_category_id,
                'created_by'       => auth()->id(),
                'assigned_to'      => $task->assigned_to,
                'title'            => $task->title.' (Copy)',
                'description'      => $task->description,
                'status'           => $task->status,
                'priority'         => $task->priority,
                'due_date'         => $task->due_date,
                'estimated_time'   => $task->estimated_time,
                'actual_time'      => null,
                'position'         => $this->nextPosition($task->task_category_id, $task->status),
            ]);

            $copy->labels()->sync($task->labels->pluck('id'));
            $this->logActivity($copy, 'duplicated', 'Task duplicated from another task.', ['source_task_id' => $task->id]);

            return $copy->load(['category', 'creator', 'assignee', 'labels']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Task duplicated successfully.',
            'task'    => $this->taskPayload($copy),
        ]);
    }

    public function comment(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $comment = TaskComment::create([
            'task_id' => $task->id,
            'user_id' => $request->user()->id,
            'body'    => $validated['body'],
        ]);

        $this->logActivity($task, 'commented', 'Comment added to task.');

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully.',
            'comment' => [
                'id'   => $comment->id,
                'body' => $comment->body,
                'user' => $request->user()->name,
                'date' => $comment->created_at->format('M d, Y h:i A'),
            ],
        ]);
    }

    public function attachment(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $file = $validated['file'];
        $path = $file->store('task-attachments', 'public');

        $attachment = TaskAttachment::create([
            'task_id'       => $task->id,
            'user_id'       => $request->user()->id,
            'path'          => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type'     => $file->getClientMimeType(),
            'size'          => $file->getSize(),
        ]);

        $this->logActivity($task, 'attachment_added', 'Attachment uploaded to task.', ['attachment_id' => $attachment->id]);

        return response()->json([
            'success' => true,
            'message' => 'Attachment uploaded successfully.',
            'attachment' => [
                'id'            => $attachment->id,
                'original_name'  => $attachment->original_name,
                'url'           => Storage::disk('public')->url($attachment->path),
            ],
        ]);
    }

    private function validateTask(Request $request, ?int $taskId = null): array
    {
        return $request->validate([
            'task_category_id' => ['required', 'integer', Rule::exists('task_categories', 'id')],
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'status'           => ['required', Rule::in(array_keys(self::STATUSES))],
            'priority'         => ['required', Rule::in(array_keys(self::PRIORITIES))],
            'due_date'         => ['nullable', 'date'],
            'assigned_to'      => ['nullable', 'integer', Rule::exists('users', 'id')],
            'estimated_time'   => ['nullable', 'numeric', 'min:0'],
            'actual_time'      => ['nullable', 'numeric', 'min:0'],
            'labels'           => ['nullable', 'string'],
            'attachments'      => ['nullable', 'array'],
            'attachments.*'    => ['file', 'max:10240'],
        ]);
    }

    private function formPayload(array $overrides = []): array
    {
        return array_merge([
            'categories' => TaskCategory::query()->orderBy('position')->orderBy('name')->get(),
            'users'      => User::query()->orderBy('name')->get(['id', 'name', 'email', 'image']),
            'statuses'   => self::STATUSES,
            'priorities' => self::PRIORITIES,
            'labels'     => TaskLabel::query()->orderBy('name')->get(['id', 'name', 'slug', 'color']),
        ], $overrides);
    }

    private function resolveSelectedCategory(Request $request, Collection $categories): ?TaskCategory
    {
        if ($categories->isEmpty()) {
            return null;
        }

        $category = null;

        if ($request->filled('category')) {
            $category = $categories->firstWhere('slug', $request->string('category')->toString());
        }

        return $category ?? $categories->first();
    }

    private function boardTasks(?int $categoryId, string $search = ''): Collection
    {
        if (! $categoryId) {
            return collect();
        }

        return Task::query()
            ->with(['category', 'creator', 'assignee', 'labels'])
            ->where('task_category_id', $categoryId)
            ->whereNull('archived_at')
            ->when($search, fn ($query) => $query->where(function ($inner) use ($search) {
                $inner->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            }))
            ->orderBy('position')
            ->get()
            ->groupBy('status');
    }

    private function summaryCounts(Collection $boardTasks): array
    {
        return collect(self::STATUSES)->mapWithKeys(function ($label, $status) use ($boardTasks) {
            return [$status => $boardTasks->get($status, collect())->count()];
        })->toArray();
    }

    private function taskPayload(Task $task): array
    {
        return [
            'id'            => $task->id,
            'title'         => $task->title,
            'description'   => $task->description,
            'status'        => $task->status,
            'priority'      => $task->priority,
            'due_date'      => optional($task->due_date)->format('M d, Y'),
            'estimated_time'=> $task->estimated_time,
            'actual_time'   => $task->actual_time,
            'position'      => $task->position,
            'archived'      => (bool) $task->archived_at,
            'category'      => $task->category?->only(['id', 'name', 'slug']),
            'creator'       => $task->creator?->only(['id', 'name', 'email', 'image']),
            'assignee'      => $task->assignee?->only(['id', 'name', 'email', 'image']),
            'labels'        => $task->labels->map(fn ($label) => $label->only(['id', 'name', 'slug', 'color']))->values(),
            'comments'      => $task->comments?->map(fn ($comment) => [
                'id'         => $comment->id,
                'body'       => $comment->body,
                'user'       => $comment->user?->name,
                'created_at'  => $comment->created_at->format('M d, Y h:i A'),
            ])->values(),
            'attachments'   => $task->attachments?->map(fn ($attachment) => [
                'id'            => $attachment->id,
                'original_name'  => $attachment->original_name,
                'url'            => Storage::disk('public')->url($attachment->path),
            ])->values(),
            'activity_logs' => $task->activityLogs?->map(fn ($activity) => [
                'id'         => $activity->id,
                'action'     => $activity->action,
                'description'=> $activity->description,
                'user'       => $activity->user?->name,
                'created_at' => $activity->created_at->format('M d, Y h:i A'),
            ])->values(),
        ];
    }

    private function nextPosition(int $categoryId, string $status): int
    {
        return (int) Task::query()
            ->where('task_category_id', $categoryId)
            ->where('status', $status)
            ->max('position') + 1;
    }

    private function syncLabels(Task $task, string $labels): void
    {
        $names = collect(explode(',', $labels))
            ->map(fn ($label) => trim($label))
            ->filter()
            ->unique()
            ->values();

        if ($names->isEmpty()) {
            $task->labels()->sync([]);

            return;
        }

        $labelIds = $names->map(function (string $name) {
            $label = TaskLabel::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'color' => $this->randomColor($name)]
            );

            return $label->id;
        });

        $task->labels()->sync($labelIds);
    }

    private function storeAttachments(Task $task, Request $request): void
    {
        if (! $request->hasFile('attachments')) {
            return;
        }

        foreach ((array) $request->file('attachments') as $file) {
            if (! $file) {
                continue;
            }

            $path = $file->store('task-attachments', 'public');

            TaskAttachment::create([
                'task_id'       => $task->id,
                'user_id'       => $request->user()->id,
                'path'          => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type'     => $file->getClientMimeType(),
                'size'          => $file->getSize(),
            ]);
        }
    }

    private function logActivity(Task $task, string $action, ?string $description = null, array $properties = []): void
    {
        TaskActivityLog::create([
            'task_id'     => $task->id,
            'user_id'     => auth()->id(),
            'action'      => $action,
            'description' => $description,
            'properties'   => $properties ?: null,
        ]);
    }

    private function randomColor(string $value): string
    {
        $palette = ['#0d6efd', '#198754', '#6f42c1', '#dc3545', '#fd7e14', '#20c997', '#0dcaf0', '#6610f2'];

        return $palette[crc32($value) % count($palette)];
    }
}
