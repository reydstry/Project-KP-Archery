<?php

namespace App\DTO\Admin;

class AdminDashboardData
{
    public function __construct(
        public readonly array $statistics,
        public readonly array $recentPendingMembers,
        public readonly array $todaySessions,
        public readonly array $alerts,
        public readonly array $activityToday,
    ) {
    }

    public function toArray(): array
    {
        return [
            'statistics' => $this->statistics,
            'recent' => [
                'pending_members' => $this->recentPendingMembers,
            ],
            'today_sessions' => $this->todaySessions,
            'alerts' => $this->alerts,
            'activity_today' => $this->activityToday,
        ];
    }
}
