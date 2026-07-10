<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TasksExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelWriter;
use Illuminate\View\View;

class TaskExportController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        return view('admin.tasks.export', [
            'users' => $this->isAdmin($user)
                ? User::query()->orderBy('name')->get(['id', 'name', 'email', 'status'])
                : collect(),
            'isTeamMember' => $this->isTeamMember($user),
            'isAdmin' => $this->isAdmin($user),
        ]);
    }

    public function download(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'mode'      => ['required', 'in:all,date,user,status'],
            'date_from' => ['nullable', 'date'],
            'date_to'   => ['nullable', 'date', 'after_or_equal:date_from'],
            'user_id'   => ['nullable', 'integer', 'exists:users,id'],
            'status'    => ['nullable', 'in:todo,in_progress,done'],
            'archive'   => ['nullable', 'boolean'],
        ])->validate();

        if ($validated['mode'] === 'date' && (empty($validated['date_from']) || empty($validated['date_to']))) {
            return back()->withErrors([
                'date_from' => 'Please provide both start and end dates for date-based export.',
            ])->withInput();
        }

        if ($validated['mode'] === 'user' && empty($validated['user_id'])) {
            return back()->withErrors([
                'user_id' => 'Please select a user for user-based export.',
            ])->withInput();
        }

        if ($validated['mode'] === 'status' && empty($validated['status'])) {
            return back()->withErrors([
                'status' => 'Please choose a task status.',
            ])->withInput();
        }

        $user = $request->user();
        $isTeamMember = $this->isTeamMember($user);

        $filters = [
            'mode'            => $validated['mode'],
            'date_from'       => $validated['date_from'] ?? null,
            'date_to'         => $validated['date_to'] ?? null,
            'user_id'         => $isTeamMember ? $user->id : ($validated['user_id'] ?? null),
            'status'          => $validated['status'] ?? null,
            'include_archived'=> (bool) ($validated['archive'] ?? false),
            'scope_to_user'   => $isTeamMember,
        ];

        $filename = match ($validated['mode']) {
            'date'   => sprintf('tasks_%s_to_%s.xlsx', $validated['date_from'], $validated['date_to']),
            'user'   => sprintf('tasks_user_%s.xlsx', $validated['user_id']),
            'status' => sprintf('tasks_%s.xlsx', $validated['status']),
            default  => 'tasks_all.xlsx',
        };

        return Excel::download(new TasksExport($filters), $filename, ExcelWriter::XLSX);
    }

    private function isAdmin(?User $user): bool
    {
        return (bool) $user?->hasRole('Admin');
    }

    private function isTeamMember(?User $user): bool
    {
        return (bool) $user?->hasRole('Team Member') && ! $user?->hasRole('Admin');
    }
}
