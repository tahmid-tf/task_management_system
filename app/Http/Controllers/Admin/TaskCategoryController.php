<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.tasks.categories', [
            'categories' => TaskCategory::query()
                ->withCount('tasks')
                ->orderBy('position')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);

        $category = TaskCategory::create([
            'name'     => $validated['name'],
            'slug'     => Str::slug($validated['name']),
            'color'    => $validated['color'] ?? '#0d6efd',
            'position' => (int) TaskCategory::max('position') + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully.',
            'category' => $category,
        ]);
    }

    public function update(Request $request, TaskCategory $taskCategory): JsonResponse
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);

        $taskCategory->update([
            'name'  => $validated['name'],
            'slug'  => Str::slug($validated['name']),
            'color' => $validated['color'] ?? $taskCategory->color,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully.',
            'category' => $taskCategory->fresh(),
        ]);
    }

    public function destroy(TaskCategory $taskCategory): JsonResponse
    {
        $taskCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully.',
        ]);
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer', Rule::exists('task_categories', 'id')],
        ]);

        foreach ($validated['ids'] as $index => $id) {
            TaskCategory::whereKey($id)->update(['position' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Categories reordered successfully.',
        ]);
    }
}
