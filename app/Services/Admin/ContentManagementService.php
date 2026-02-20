<?php

namespace App\Services\Admin;

use App\Models\Achievement;
use App\Models\News;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ContentManagementService
{
    public function listNews()
    {
        return News::query()
            ->orderBy('publish_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);
    }

    public function createNews(array $payload, ?UploadedFile $photo): News
    {
        if ($photo) {
            $payload['photo_path'] = $photo->store('news', 'public');
        }

        unset($payload['photo']);

        return News::query()->create($payload);
    }

    public function updateNews(News $news, array $payload, ?UploadedFile $photo): News
    {
        if ($photo) {
            if ($news->photo_path && Storage::disk('public')->exists($news->photo_path)) {
                Storage::disk('public')->delete($news->photo_path);
            }

            $payload['photo_path'] = $photo->store('news', 'public');
        }

        unset($payload['photo']);
        $news->update($payload);

        return $news->fresh();
    }

    public function deleteNews(News $news): void
    {
        if ($news->photo_path && Storage::disk('public')->exists($news->photo_path)) {
            Storage::disk('public')->delete($news->photo_path);
        }

        $news->delete();
    }

    public function listAchievements()
    {
        return Achievement::query()
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);
    }

    public function createAchievement(array $payload, ?UploadedFile $photo): Achievement
    {
        if (($payload['type'] ?? null) === 'club') {
            $payload['member_id'] = null;
        }

        if ($photo) {
            $payload['photo_path'] = $photo->store('achievements', 'public');
        }

        unset($payload['photo']);

        return Achievement::query()->create($payload);
    }

    public function updateAchievement(Achievement $achievement, array $payload, ?UploadedFile $photo): Achievement
    {
        $type = $payload['type'] ?? $achievement->type;
        if ($type === 'club') {
            $payload['member_id'] = null;
        }

        if ($photo) {
            if ($achievement->photo_path && Storage::disk('public')->exists($achievement->photo_path)) {
                Storage::disk('public')->delete($achievement->photo_path);
            }

            $payload['photo_path'] = $photo->store('achievements', 'public');
        }

        unset($payload['photo']);
        $achievement->update($payload);

        return $achievement->fresh();
    }

    public function deleteAchievement(Achievement $achievement): void
    {
        if ($achievement->photo_path && Storage::disk('public')->exists($achievement->photo_path)) {
            Storage::disk('public')->delete($achievement->photo_path);
        }

        $achievement->delete();
    }
}
