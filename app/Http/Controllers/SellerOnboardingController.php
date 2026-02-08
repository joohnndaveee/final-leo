<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerOnboardingController extends Controller
{
    /**
     * Show the seller application form for a logged-in user.
     */
    public function showForm()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // If already a seller, show shop settings inside seller layout
        if (($user->role ?? 'buyer') === 'seller') {
            return view('seller.settings', compact('user'));
        }

        // Normal users see the application page in the buyer layout
        return view('seller.apply', compact('user'));
    }

    /**
     * Handle seller application submission.
     */
    public function submit(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'business_address' => 'required|string|max:500',
            'business_id_number' => 'nullable|string|max:255',
            'business_notes' => 'nullable|string|max:2000',
        ]);

        $user->shop_name = $validated['shop_name'];
        $user->business_address = $validated['business_address'];
        $user->business_id_number = $validated['business_id_number'] ?? null;
        $user->business_notes = $validated['business_notes'] ?? null;

        // Mark that this user has applied to become a seller.
        // Keep their role as buyer until the admin approves.
        $user->seller_status = $user->seller_status ?? 'pending';

        $user->save();

        return redirect()
            ->route('seller.application.status')
            ->with('success', 'Your seller application has been submitted and is pending approval.');
    }

    /**
     * Show the application status page for a seller.
     */
    public function status()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // If user has not applied yet, send them to the application form
        if ($user->seller_status === null) {
            return redirect()->route('seller.apply');
        }

        return view('seller.application-status', compact('user'));
    }
}

