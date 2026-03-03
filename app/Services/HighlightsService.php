<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\News;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HighlightsService
{
    /**
     * Get merged highlights (news + achievements), sorted by date descending.
     *
     * @param  int  $limit  Max items per model
     * @param  int  $excerptLength  Truncate excerpt to this length
     * @return Collection<int, array>
     */
    public function getHighlights(int $limit = 12, int $excerptLength = 150): Collection
    {
        $newsItems = $this->getNewsItems($limit, $excerptLength);
        $achievementItems = $this->getAchievementItems($limit, $excerptLength);

        return $newsItems
            ->concat($achievementItems)
            ->sortByDesc('timestamp')
            ->values();
    }

    /**
     * @return Collection<int, array>
     */
    public function getNewsItems(int $limit = 12, int $excerptLength = 150): Collection
    {
        return News::query()
            ->published()
            ->orderBy('publish_date', 'desc')
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get()
            ->map(fn (News $news) => [
                'id' => $news->id,
                'type' => 'news',
                'badge' => __('gallery.type_news'),
                'badge_icon' => '📰',
                'image' => $news->photo_url ?? asset('asset/img/latarbelakanglogin.jpeg'),
                'title' => $news->title,
                'date' => $news->publish_date?->format('d F Y'),
                'timestamp' => $news->publish_date?->timestamp ?? 0,
                'excerpt' => Str::limit(strip_tags($news->content), $excerptLength),
                'url' => route('news.detail', $news->id),
            ]);
    }

    /**
     * @return Collection<int, array>
     */
    public function getAchievementItems(int $limit = 12, int $excerptLength = 150): Collection
    {
        return Achievement::query()
            ->published()
            ->with('member')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get()
            ->map(fn (Achievement $achievement) => [
                'id' => $achievement->id,
                'type' => 'achievement',
                'badge' => __('gallery.type_achievement'),
                'badge_icon' => self::resolveAchievementIcon($achievement->title),
                'image' => $achievement->photo_url ?? asset('asset/img/latarbelakanglogin.jpeg'),
                'title' => $achievement->title,
                'date' => $achievement->date?->format('d F Y'),
                'timestamp' => $achievement->date?->timestamp ?? 0,
                'excerpt' => Str::limit(strip_tags($achievement->description), $excerptLength),
                'member' => $achievement->type === 'member' && $achievement->member
                    ? $achievement->member->name
                    : null,
                'url' => route('achievement.detail', $achievement->id),
            ]);
    }

    /**
     * Determine the badge icon based on achievement title keywords.
     */
    public static function resolveAchievementIcon(string $title): string
    {
        $lower = strtolower($title);

        if (str_contains($lower, 'juara 1') || str_contains($lower, 'gold') || str_contains($lower, '1st place')) {
            return '🥇';
        }

        if (str_contains($lower, 'juara 2') || str_contains($lower, 'silver') || str_contains($lower, '2nd place')) {
            return '🥈';
        }

        if (str_contains($lower, 'juara 3') || str_contains($lower, 'bronze') || str_contains($lower, '3rd place')) {
            return '🥉';
        }

        return '🏆';
    }
}
