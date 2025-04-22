<?php

namespace App\Policies;

use App\Models\Audit;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AuditPolicy
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
    public function view(User $user, Audit $audit): bool
    {
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
        return $user->hasPermissionTo('create audits');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Audit $audit): bool
    {
        // Lead auditor can update
        if ($audit->lead_auditor_id === $user->id) {
            return true;
        }

        return $user->hasPermissionTo('edit audits');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Audit $audit): bool
    {
        return $user->hasPermissionTo('delete audits');
    }

    /**
     * Determine whether the user can plan an audit.
     */
    public function planAudit(User $user): bool
    {
        return $user->hasPermissionTo('plan audits');
    }

    /**
     * Determine whether the user can conduct an audit.
     */
    public function conductAudit(User $user, Audit $audit): bool
    {
        // Lead auditor or assigned auditor can conduct
        if ($audit->lead_auditor_id === $user->id || $audit->auditors()->where('user_id', $user->id)->exists()) {
            return true;
        }

        return $user->hasPermissionTo('conduct audits');
    }

    /**
     * Determine whether the user can respond to findings.
     */
    public function respondToFindings(User $user, Audit $audit): bool
    {
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
     * Determine whether the user can verify findings.
     */
    public function verifyFindings(User $user, Audit $audit): bool
    {
        // Lead auditor can verify findings
        if ($audit->lead_auditor_id === $user->id) {
            return true;
        }

        return $user->hasPermissionTo('verify findings');
    }
}
