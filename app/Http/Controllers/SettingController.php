<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index()
    {
        $groups = Setting::select('group')->distinct()->pluck('group');
        $settings = [];

        foreach ($groups as $group) {
            $settings = array_merge($settings, Setting::getGroup($group));
        }

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['settings'] as $group => $groupSettings) {
                foreach ($groupSettings as $key => $value) {
                    $existing = Setting::where('group', $group)->where('key', $key)->first();
                    $type = $existing?->type ?? 'string';

                    Setting::set($group, $key, $value, $type);
                }
            }

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'update_settings',
                subject: 'App\\Models\\Setting',
                description: 'System settings updated'
            );

            DB::commit();
            return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }
}
