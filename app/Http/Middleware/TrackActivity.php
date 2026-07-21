<?php

namespace App\Http\Middleware;

use App\Services\ActivityService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackActivity
{
    protected ActivityService $activityService;

    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    public function handle(Request $request, Closure $next, ?string $action = null)
    {
        $response = $next($request);

        if (Auth::check() && $request->method() !== 'GET' && !$request->expectsJson()) {
            $user = Auth::user();
            $routeName = $request->route()->getName() ?? 'unknown';
            $logAction = $action ?? str_replace('.', '_', $routeName);

            $this->activityService->log(
                user: $user,
                action: $logAction,
                subject: $this->guessSubject($request),
                description: ucfirst(str_replace('_', ' ', $logAction)) . ' on ' . $request->path()
            );
        }

        return $response;
    }

    protected function guessSubject(Request $request): string
    {
        $segments = $request->segments();
        $resource = $segments[0] ?? 'system';

        return match ($resource) {
            'items' => \App\Models\Item::class,
            'categories' => \App\Models\Category::class,
            'suppliers' => \App\Models\Supplier::class,
            'customers' => \App\Models\Customer::class,
            'purchases' => \App\Models\Purchase::class,
            'sales' => \App\Models\Sale::class,
            'expenses' => \App\Models\Expense::class,
            'roles' => \App\Models\Role::class,
            'users' => \App\Models\User::class,
            default => 'App\\System',
        };
    }
}
