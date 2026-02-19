<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    public function branding()
    {
        $settings = SiteSetting::find(1);

        return view('admin.settings.branding', [
            'settings' => $settings,
            'siteLogoUrl' => asset($settings?->site_logo_path ?: 'images/logo.png'),
        ]);
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'site_logo' => 'required|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        $path = 'images/logo.png';
        if ($request->hasFile('site_logo')) {
            $file = $request->file('site_logo');
            $filename = 'site_logo_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploaded_img'), $filename);
            $path = 'uploaded_img/' . $filename;
        }

        SiteSetting::updateOrCreate(
            ['id' => 1],
            [
                'site_logo_path' => $path,
                'updated_by' => auth('admin')->id(),
            ]
        );

        return redirect()
            ->route('admin.settings.branding')
            ->with('success', 'Logo updated successfully.');
    }
}

