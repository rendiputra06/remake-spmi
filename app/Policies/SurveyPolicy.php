<?php

namespace App\Policies;

use App\Models\Survey;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SurveyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view surveys');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Survey $survey): bool
    {
        // Creator can always view their survey
        if ($user->id === $survey->created_by) {
            return true;
        }

        // User from related faculty/department/unit can view
        if (
            ($survey->faculty_id && $user->profile?->faculty_id === $survey->faculty_id) ||
            ($survey->department_id && $user->profile?->department_id === $survey->department_id) ||
            ($survey->unit_id && $user->profile?->unit_id === $survey->unit_id)
        ) {
            return true;
        }

        return $user->hasPermissionTo('view surveys');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create surveys');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Survey $survey): bool
    {
        // Creator can always update their survey
        if ($user->id === $survey->created_by) {
            return true;
        }

        return $user->hasPermissionTo('edit surveys');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Survey $survey): bool
    {
        // Creator can always delete their survey
        if ($user->id === $survey->created_by) {
            return true;
        }

        return $user->hasPermissionTo('delete surveys');
    }

    /**
     * Determine whether the user can fill the survey.
     */
    public function fill(User $user, Survey $survey): bool
    {
        // Check if survey is active
        if ($survey->status !== 'active') {
            return false;
        }

        // Check if survey has started and not ended
        if (
            ($survey->start_date && $survey->start_date > now()) ||
            ($survey->end_date && $survey->end_date < now())
        ) {
            return false;
        }

        // Check if target audience matches
        if (
            $survey->target_audience &&
            !str_contains(strtolower($user->profile?->position ?? ''), strtolower($survey->target_audience))
        ) {
            return false;
        }

        // Check if user is in target faculty/department/unit
        if (
            ($survey->faculty_id && $user->profile?->faculty_id !== $survey->faculty_id) &&
            ($survey->department_id && $user->profile?->department_id !== $survey->department_id) &&
            ($survey->unit_id && $user->profile?->unit_id !== $survey->unit_id) &&
            (!is_null($survey->faculty_id) || !is_null($survey->department_id) || !is_null($survey->unit_id))
        ) {
            return false;
        }

        return $user->hasPermissionTo('fill surveys');
    }

    /**
     * Determine whether the user can analyze survey results.
     */
    public function analyze(User $user, Survey $survey): bool
    {
        // Creator can always analyze their survey
        if ($user->id === $survey->created_by) {
            return true;
        }

        return $user->hasPermissionTo('analyze surveys');
    }
}
