<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyManagementController extends Controller
{
    /**
     * Mempublikasikan survei
     */
    public function publish(Survey $record)
    {
        // Periksa otorisasi
        if (!Auth::user()->can('update', $record)) {
            abort(403, 'Unauthorized action.');
        }

        // Ubah status survei menjadi active
        $record->status = 'active';
        $record->updated_by = Auth::id();
        $record->save();

        // Redirect kembali ke halaman view dengan notifikasi
        return redirect()
            ->route('filament.admin.resources.surveys.view', ['record' => $record->id])
            ->with('success', 'Survei berhasil dipublikasikan');
    }

    /**
     * Menutup survei
     */
    public function close(Survey $record)
    {
        // Periksa otorisasi
        if (!Auth::user()->can('update', $record)) {
            abort(403, 'Unauthorized action.');
        }

        // Ubah status survei menjadi closed
        $record->status = 'closed';
        $record->updated_by = Auth::id();
        $record->save();

        // Redirect kembali ke halaman view dengan notifikasi
        return redirect()
            ->route('filament.admin.resources.surveys.view', ['record' => $record->id])
            ->with('success', 'Survei berhasil ditutup');
    }
}
