<?php

namespace Tests\Feature\Admin;

use App\Enums\StatusMember;
use App\Enums\UserRoles;
use App\Jobs\SendBroadcastJob;
use App\Models\Broadcast;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class BroadcastFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_broadcast_and_dispatch_queue_job(): void
    {
        Bus::fake();

        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);

        Member::factory()->create([
            'status' => StatusMember::STATUS_ACTIVE->value,
            'phone' => '081111111111',
        ]);
        Member::factory()->create([
            'status' => StatusMember::STATUS_ACTIVE->value,
            'phone' => '082222222222',
        ]);
        Member::factory()->create([
            'status' => StatusMember::STATUS_INACTIVE->value,
            'phone' => '083333333333',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.whatsapp.broadcast.store'), [
                'title' => 'Latihan Gabungan',
                'event_date' => '2026-03-10',
                'message' => 'Jangan lupa hadir tepat waktu.',
            ]);

        $broadcast = Broadcast::query()->firstOrFail();

        $response->assertRedirect(route('admin.whatsapp.logs.show', $broadcast));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('broadcasts', [
            'id' => $broadcast->id,
            'title' => 'Latihan Gabungan',
            'status' => 'pending',
            'created_by' => $admin->id,
            'total_target' => 2,
        ]);

        $this->assertDatabaseCount('broadcast_logs', 2);

        Bus::assertDispatched(SendBroadcastJob::class, function (SendBroadcastJob $job) use ($broadcast) {
            return $job->broadcastId === $broadcast->id;
        });
    }

    public function test_create_broadcast_requires_title_event_date_and_message(): void
    {
        Bus::fake();

        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);

        $response = $this->from(route('admin.whatsapp.broadcast.create'))
            ->actingAs($admin)
            ->post(route('admin.whatsapp.broadcast.store'), []);

        $response->assertRedirect(route('admin.whatsapp.broadcast.create'));
        $response->assertSessionHasErrors(['title', 'event_date', 'message']);

        $this->assertDatabaseCount('broadcasts', 0);
        Bus::assertNotDispatched(SendBroadcastJob::class);
    }
}
