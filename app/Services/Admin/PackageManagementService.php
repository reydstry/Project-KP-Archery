<?php

namespace App\Services\Admin;

use App\Enums\StatusMember;
use App\Models\Member;
use App\Models\MemberPackage;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PackageManagementService
{
    public function listPackages(): array
    {
        return [
            'message' => 'Data packages berhasil diambil',
            'data' => Package::query()
                ->select([
                    'id',
                    'name',
                    'description',
                    'price',
                    'duration_days',
                    'session_count',
                    'is_active',
                    'created_at',
                    'updated_at',
                ])
                ->latest('created_at')
                ->get(),
        ];
    }

    public function createPackage(array $payload): Package
    {
        $payload['is_active'] = true;

        return Package::query()->create($payload);
    }

    public function showPackage(Package $package): array
    {
        return [
            'message' => 'Data package berhasil diambil',
            'data' => $package,
        ];
    }

    public function updatePackage(Package $package, array $payload): array
    {
        $package->update($payload);

        return [
            'message' => 'Package berhasil diupdate',
            'data' => $package->fresh(),
        ];
    }

    public function deactivatePackage(Package $package): array
    {
        $package->update(['is_active' => false]);

        return [
            'message' => 'Package berhasil dihapus',
        ];
    }

    public function restorePackage(Package $package): array
    {
        $package->update(['is_active' => true]);

        return [
            'message' => 'Package berhasil diaktifkan',
            'data' => $package->fresh(),
        ];
    }

    public function listMemberPackages(): array
    {
        $memberPackages = MemberPackage::query()
            ->select([
                'id',
                'member_id',
                'package_id',
                'total_sessions',
                'used_sessions',
                'start_date',
                'end_date',
                'is_active',
                'validated_by',
                'validated_at',
                'created_at',
                'updated_at',
            ])
            ->with([
                'member:id,name,phone,status,is_active',
                'package:id,name,price,duration_days,session_count,is_active',
                'validator:id,name,email',
            ])
            ->latest('created_at')
            ->get();

        return [
            'message' => 'Data member packages berhasil diambil',
            'data' => $memberPackages,
        ];
    }

    public function assignPackageToMember(Member $member, int $packageId, string $startDate, ?int $validatorId): array
    {
        if (!$member->is_active || $member->status === StatusMember::STATUS_INACTIVE->value) {
            return [
                'error' => true,
                'status' => 422,
                'body' => [
                    'message' => 'Member is inactive',
                ],
            ];
        }

        $package = Package::query()->findOrFail($packageId);

        if (property_exists($package, 'is_active') && !$package->is_active) {
            return [
                'error' => true,
                'status' => 422,
                'body' => ['message' => 'Package is inactive'],
            ];
        }

        $start = Carbon::parse($startDate);
        $end = $start->copy()->addDays($package->duration_days);

        try {
            DB::beginTransaction();

            $memberPackage = MemberPackage::query()
                ->where('member_id', $member->id)
                ->latest('id')
                ->first();

            $isCreate = false;
            if (!$memberPackage) {
                $memberPackage = new MemberPackage();
                $memberPackage->member_id = $member->id;
                $isCreate = true;
            }

            $memberPackage->package_id = $package->id;
            $memberPackage->total_sessions = $package->session_count;
            $memberPackage->used_sessions = 0;
            $memberPackage->start_date = $start;
            $memberPackage->end_date = $end;
            $memberPackage->is_active = true;
            $memberPackage->validated_by = $validatorId;
            $memberPackage->validated_at = now();
            $memberPackage->save();

            if (
                !$member->is_active
                || $member->status === StatusMember::STATUS_PENDING->value
                || $member->status === StatusMember::STATUS_INACTIVE->value
            ) {
                $member->update([
                    'is_active' => true,
                    'status' => StatusMember::STATUS_ACTIVE->value,
                ]);
            }

            DB::commit();

            return [
                'error' => false,
                'status' => $isCreate ? 201 : 200,
                'body' => [
                    'message' => 'Package assigned successfully',
                    'data' => $memberPackage->load([
                        'member:id,name,phone,status,is_active',
                        'package:id,name,price,duration_days,session_count,is_active',
                        'validator:id,name,email',
                    ]),
                ],
            ];
        } catch (\Throwable $exception) {
            DB::rollBack();

            return [
                'error' => true,
                'status' => 500,
                'body' => [
                    'message' => 'Failed to assign package',
                    'error' => $exception->getMessage(),
                ],
            ];
        }
    }

    public function showMemberPackage(MemberPackage $memberPackage)
    {
        return $memberPackage->load([
            'member:id,name,phone,status,is_active',
            'package:id,name,price,duration_days,session_count,is_active',
            'validator:id,name,email',
        ]);
    }

    public function listPackagesByMember(Member $member)
    {
        return MemberPackage::query()
            ->select([
                'id',
                'member_id',
                'package_id',
                'total_sessions',
                'used_sessions',
                'start_date',
                'end_date',
                'is_active',
                'validated_by',
                'validated_at',
                'created_at',
                'updated_at',
            ])
            ->with([
                'package:id,name,price,duration_days,session_count,is_active',
                'validator:id,name,email',
            ])
            ->where('member_id', $member->id)
            ->latest('created_at')
            ->get();
    }
}
