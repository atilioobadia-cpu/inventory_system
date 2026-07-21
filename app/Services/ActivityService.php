<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ActivityService
{
    public function log(
        User $user,
        string $action,
        $subject,
        ?string $description = null,
        array $oldValues = null,
        array $newValues = null
    ): object {
        $data = [
            'user_id'      => $user->id,
            'action'       => $action,
            'subject_type' => is_string($subject) ? $subject : get_class($subject),
            'subject_id'   => is_string($subject) ? null : $subject->id,
            'description'  => $description,
            'old_values'   => $oldValues ? json_encode($oldValues) : null,
            'new_values'   => $newValues ? json_encode($newValues) : null,
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
            'created_at'   => now(),
            'updated_at'   => now(),
        ];

        DB::table('activity_logs')->insert($data);

        return (object) $data;
    }

    public function getRecent(int $limit = 10): Collection
    {
        return DB::table('activity_logs')
            ->join('users', 'activity_logs.user_id', '=', 'users.id')
            ->select('activity_logs.*', 'users.name as user_name')
            ->orderBy('activity_logs.created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getForSubject($subject): Collection
    {
        $subjectType = is_string($subject) ? $subject : get_class($subject);
        $subjectId   = is_string($subject) ? null : $subject->id;

        return DB::table('activity_logs')
            ->join('users', 'activity_logs.user_id', '=', 'users.id')
            ->select('activity_logs.*', 'users.name as user_name')
            ->where('activity_logs.subject_type', $subjectType)
            ->where('activity_logs.subject_id', $subjectId)
            ->orderBy('activity_logs.created_at', 'desc')
            ->get();
    }

    public function getForUser(User $user, int $limit = 50): Collection
    {
        return DB::table('activity_logs')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
