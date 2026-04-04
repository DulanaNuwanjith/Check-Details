<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

/**
 * @method static findOrFail(int $id)
 * @method static create(array $array)
 * @method static count()
 * @method static where(string $string, int $int)
 * @method static latest()
 */
class BankAccounts extends Model
{
    protected $fillable = [
        'bank_name',
        'branch_name',
        'bank_code',
        'company_name',
        'is_active',
        'remarks'
    ];

    /**
     * Boot method to hook into model events
     */
    protected static function booted(): void
    {
        // ✅ CREATE
        static::created(static function ($bankAccount) {
            self::logActivity('created', null, $bankAccount);
        });

        // ✅ UPDATE
        static::updated(static function ($bankAccount) {
            self::logActivity(
                'updated',
                $bankAccount->getOriginal(),
                $bankAccount->getChanges(),
                $bankAccount
            );
        });

        // ✅ DELETE
        static::deleted(static function ($bankAccount) {
            self::logActivity('deleted', $bankAccount->toArray(), null, $bankAccount);
        });
    }

    /**
     * Central logging function
     */
    protected static function logActivity($action, $oldValues = null, $newValues = null, $bankAccount = null): void
    {
        try {
            ActivityLog::create([
                'module'      => 'bank_account',
                'module_id'   => $bankAccount?->id,
                'action'      => $action,
                'user_id'     => Auth::id(),

                'old_values'  => $oldValues,
                'new_values'  => $newValues,

                'description' => self::generateDescription($action, $bankAccount),

                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),

                'status'      => $bankAccount?->is_active,
            ]);
        } catch (\Exception $e) {
            // Prevent breaking the app if logging fails
        }
    }

    /**
     * Generate human-readable description
     */
    protected static function generateDescription($action, $bankAccount): ?string
    {
        if (!$bankAccount) {
            return null;
        }

        return match ($action) {
            'created' => "Bank account {$bankAccount->bank_name} ({$bankAccount->company_name}) created.",
            'updated' => "Bank account {$bankAccount->bank_name} ({$bankAccount->company_name}) updated.",
            'deleted' => "Bank account {$bankAccount->bank_name} ({$bankAccount->company_name}) deleted.",
            default   => "Bank account {$bankAccount->bank_name} ({$bankAccount->company_name}) {$action}.",
        };
    }
}
