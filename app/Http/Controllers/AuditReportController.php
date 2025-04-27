<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\User;
use App\Notifications\AuditReportNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

class AuditReportController extends Controller
{
    /**
     * Tampilkan form distribusi laporan audit
     */
    public function showDistributionForm(Audit $audit)
    {
        // Hanya izinkan lead auditor atau user dengan permission 'distribute reports'
        if (Auth::id() !== $audit->lead_auditor_id && !Auth::user()->can('distribute reports')) {
            abort(403, 'Tidak diizinkan mendistribusikan laporan audit ini');
        }

        $users = User::role(['pimpinan', 'auditor', 'auditee', 'staff'])->get();

        return view('audits.distribute', [
            'audit' => $audit,
            'users' => $users
        ]);
    }

    /**
     * Distribusikan laporan audit
     */
    public function distribute(Request $request, Audit $audit)
    {
        // Validasi input
        $validated = $request->validate([
            'recipients' => 'required|array',
            'recipients.*' => 'exists:users,id',
            'message' => 'nullable|string',
        ]);

        // Kirim notifikasi ke setiap penerima
        $recipients = User::whereIn('id', $validated['recipients'])->get();

        Notification::send($recipients, new AuditReportNotification(
            $audit,
            $validated['message'] ?? null
        ));

        return redirect()->route('audits.show', $audit)
            ->with('success', 'Laporan audit telah berhasil didistribusikan kepada ' . $recipients->count() . ' penerima.');
    }

    /**
     * Generate laporan audit dalam format PDF
     */
    public function generatePDF(Audit $audit)
    {
        // Ambil semua data yang dibutuhkan untuk report
        $findings = $audit->findings;

        $pdf = PDF::loadView('audits.report-pdf', [
            'audit' => $audit,
            'findings' => $findings,
        ]);

        return $pdf->download('laporan-audit-' . $audit->id . '.pdf');
    }
}
