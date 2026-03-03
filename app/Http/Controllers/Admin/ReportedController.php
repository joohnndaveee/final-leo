<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportedController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');
        $type   = $request->input('type',   'all');

        $query = Report::with(['reporter'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->when($type   !== 'all', fn($q) => $q->where('reported_type', $type))
            ->orderByDesc('created_at');

        $reports = $query->paginate(20)->withQueryString();

        $stats = [
            'pending'  => Report::where('status', 'pending')->count(),
            'reviewed' => Report::where('status', 'reviewed')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'dismissed'=> Report::where('status', 'dismissed')->count(),
        ];

        return view('admin.reported', compact('reports', 'stats', 'status', 'type'));
    }

    public function show($id)
    {
        $report = Report::with(['reporter'])->findOrFail($id);

        // Eager-load the reported entity
        $reportedEntity = null;
        if ($report->reported_type === 'product') {
            $reportedEntity = Product::with('seller')->find($report->reported_id);
        } elseif ($report->reported_type === 'seller') {
            $reportedEntity = Seller::find($report->reported_id);
        } elseif ($report->reported_type === 'user') {
            $reportedEntity = User::find($report->reported_id);
        }

        return view('admin.reported-show', compact('report', 'reportedEntity'));
    }

    public function update(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $oldStatus = (string) $report->status;
        $oldNotes = (string) ($report->admin_notes ?? '');

        $request->validate([
            'status'      => 'required|in:pending,reviewed,resolved,dismissed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $report->update([
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => Auth::guard('admin')->id(),
            'reviewed_at' => now(),
        ]);

        $newStatus = (string) $report->status;
        $newNotes = (string) ($report->admin_notes ?? '');
        $statusChanged = $oldStatus !== $newStatus;
        $notesChanged = $oldNotes !== $newNotes;

        // Notify only user reporters when admin updates report progress/notes.
        if ($report->reporter_type === 'user' && $report->reporter_id && ($statusChanged || $notesChanged)) {
            $statusLabel = ucfirst($newStatus);
            $entity = $report->getReportedName();
            $message = "Your report #{$report->id} ({$entity}) is now {$statusLabel}.";
            if ($notesChanged && trim($newNotes) !== '') {
                $message .= " Admin note: " . trim($newNotes);
            }

            Notification::notifyUser(
                (int) $report->reporter_id,
                'report_update',
                'Report Status Updated',
                $message,
                (int) $report->id,
                'report'
            );
        }

        return redirect()->route('admin.reported.index')
                         ->with('success', 'Report updated successfully.');
    }

    public function destroy($id)
    {
        Report::findOrFail($id)->delete();
        return redirect()->route('admin.reported.index')->with('success', 'Report deleted.');
    }
}
