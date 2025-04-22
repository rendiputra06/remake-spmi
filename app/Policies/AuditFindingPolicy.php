<?php

namespace App\Policies;

use App\Models\AuditFinding;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AuditFindingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view audits');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AuditFinding $auditFinding): bool
    {
        $audit = $auditFinding->audit;

        // If user is an auditor for this audit
        if ($audit->auditors()->where('user_id', $user->id)->exists()) {
            return true;
        }

        // If user is lead auditor
        if ($audit->lead_auditor_id === $user->id) {
            return true;
        }

        // If user is from faculty/department/unit being audited
        if (
            ($audit->faculty_id && $user->profile?->faculty_id === $audit->faculty_id) ||
            ($audit->department_id && $user->profile?->department_id === $audit->department_id) ||
            ($audit->unit_id && $user->profile?->unit_id === $audit->unit_id)
        ) {
            return true;
        }

        return $user->hasPermissionTo('view audits');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('conduct audits');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AuditFinding $auditFinding): bool
    {
        // Creator of finding can update
        if ($auditFinding->created_by === $user->id) {
            return true;
        }

        // Lead auditor can update
        if ($auditFinding->audit->lead_auditor_id === $user->id) {
            return true;
        }

        return $user->hasPermissionTo('conduct audits');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AuditFinding $auditFinding): bool
    {
        // Only lead auditor or creator can delete a finding
        if ($auditFinding->created_by === $user->id || $auditFinding->audit->lead_auditor_id === $user->id) {
            return true;
        }

        return $user->hasPermissionTo('conduct audits');
    }

    /**
     * Determine whether the user can respond to the finding.
     */
    public function respond(User $user, AuditFinding $auditFinding): bool
    {
        $audit = $auditFinding->audit;

        // Users from the audited entity can respond to findings
        if (
            ($audit->faculty_id && $user->profile?->faculty_id === $audit->faculty_id) ||
            ($audit->department_id && $user->profile?->department_id === $audit->department_id) ||
            ($audit->unit_id && $user->profile?->unit_id === $audit->unit_id)
        ) {
            return true;
        }

        return $user->hasPermissionTo('respond to findings');
    }

    /**
     * Determine whether the user can verify the finding.
     */
    public function verify(User $user, AuditFinding $auditFinding): bool
    {
        // Lead auditor can verify findings
        if ($auditFinding->audit->lead_auditor_id === $user->id) {
            return true;
        }

        return $user->hasPermissionTo('verify findings');
    }
}
