<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_export_monthly_report(): void
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);

        $response = $this->actingAs($admin, 'sanctum')
            ->get('/api/admin/reports/export?mode=monthly&month=1&year=2026');

        $response->assertOk();

        $contentDisposition = (string) $response->headers->get('content-disposition');
        $this->assertStringContainsString('attachment;', $contentDisposition);
        $this->assertStringContainsString('report-monthly-2026-01.xlsx', $contentDisposition);
    }

    public function test_admin_can_export_weekly_report(): void
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);

        $response = $this->actingAs($admin, 'sanctum')
            ->get('/api/admin/reports/export?mode=weekly&start_date=2026-01-01&end_date=2026-01-07');

        $response->assertOk();

        $contentDisposition = (string) $response->headers->get('content-disposition');
        $this->assertStringContainsString('attachment;', $contentDisposition);
        $this->assertStringContainsString('report-weekly-2026-01-01-to-2026-01-07.xlsx', $contentDisposition);
    }

    public function test_member_cannot_export_admin_report(): void
    {
        $member = User::factory()->create(['role' => UserRoles::MEMBER]);

        $this->actingAs($member, 'sanctum')
            ->get('/api/admin/reports/export?mode=monthly&month=1&year=2026')
            ->assertForbidden()
            ->assertJson([
                'message' => 'Forbidden. Anda tidak memiliki akses.',
            ]);
    }

    public function test_export_requires_valid_period_parameters(): void
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/reports/export?mode=monthly&month=13&year=2026')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['month']);

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/reports/export?mode=weekly&start_date=2026-01-10&end_date=2026-01-01')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['end_date']);
    }
}
