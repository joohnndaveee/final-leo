<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function create(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please login to submit a report.');
        }

        $type       = $request->input('type', 'product'); // product | seller
        $reportedId = $request->input('id');

        $reportedEntity = null;
        if ($type === 'product' && $reportedId) {
            $reportedEntity = Product::with('seller')->find($reportedId);
        } elseif ($type === 'seller' && $reportedId) {
            $reportedEntity = Seller::find($reportedId);
        }

        return view('report', compact('type', 'reportedId', 'reportedEntity'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please login to submit a report.');
        }

        $request->validate([
            'reported_type' => 'required|in:product,seller,user',
            'reported_id'   => 'required|integer',
            'reason'        => 'required|string|max:100',
            'description'   => 'nullable|string|max:1000',
            'evidence_image'=> 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Prevent duplicate reports
        $existing = Report::where('reporter_id',   Auth::id())
                          ->where('reported_type', $request->reported_type)
                          ->where('reported_id',   $request->reported_id)
                          ->where('status', 'pending')
                          ->first();

        if ($existing) {
            return back()->with('warning', 'You have already submitted a pending report for this item.');
        }

        $evidencePath = null;
        if ($request->hasFile('evidence_image')) {
            $file = $request->file('evidence_image');
            $evidencePath = 'reports/' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploaded_img/reports'), $evidencePath);
        }

        Report::create([
            'reporter_id'   => Auth::id(),
            'reporter_type' => 'user',
            'reported_type' => $request->reported_type,
            'reported_id'   => $request->reported_id,
            'reason'        => $request->reason,
            'description'   => $request->description,
            'evidence_image'=> $evidencePath,
            'status'        => 'pending',
        ]);

        return redirect()->back()->with('success', 'Thank you — your report has been submitted and will be reviewed by our team.');
    }
}
