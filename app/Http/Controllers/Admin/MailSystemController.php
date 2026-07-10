<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MailSystemController extends Controller
{
    public function index(): View
    {
        return view('admin.mail-system.index', [
            'enabled' => AppSetting::mailSystemEnabled(),
        ]);
    }

    public function update(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'enabled' => ['required', 'boolean'],
        ]);

        AppSetting::setMailSystemEnabled($validated['enabled']);

        $message = $validated['enabled']
            ? 'Mail system turned on successfully.'
            : 'Mail system turned off successfully.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'enabled' => (bool) $validated['enabled'],
            ]);
        }

        return redirect()
            ->route('admin.mail-system.index')
            ->with('success', $message);
    }
}
