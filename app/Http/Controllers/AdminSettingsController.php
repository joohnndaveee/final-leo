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
            'settings'    => $settings,
            'siteLogoUrl' => asset($settings?->site_logo_path ?: 'images/logo.png'),
            'heroBgUrl'   => $settings?->hero_bg_path ? asset($settings->hero_bg_path) : null,
        ]);
    }

    public function updateSeasonalBanner(Request $request)
    {
        $request->validate([
            'seasonal_banner_enabled'    => 'nullable|boolean',
            'seasonal_banner_bg_color'   => ['nullable', 'string', 'max:20', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'seasonal_banner_text_color' => ['nullable', 'string', 'max:20', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'seasonal_banner_message'    => 'nullable|string|max:255',
        ]);

        SiteSetting::updateOrCreate(
            ['id' => 1],
            [
                'seasonal_banner_enabled'    => (bool) $request->boolean('seasonal_banner_enabled'),
                'seasonal_banner_bg_color'   => $request->seasonal_banner_bg_color ?: null,
                'seasonal_banner_text_color' => $request->seasonal_banner_text_color ?: null,
                'seasonal_banner_message'    => $request->seasonal_banner_message ?: null,
                'updated_by'                 => auth('admin')->id(),
            ]
        );

        return redirect()
            ->route('admin.settings.branding')
            ->with('success', 'Seasonal banner updated successfully.');
    }

    public function updateHeroBg(Request $request)
    {
        $request->validate([
            'hero_bg' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $file     = $request->file('hero_bg');
        $filename = 'hero_bg_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
        $file->move(public_path('uploaded_img'), $filename);
        $path = 'uploaded_img/' . $filename;

        SiteSetting::updateOrCreate(
            ['id' => 1],
            [
                'hero_bg_path' => $path,
                'updated_by'   => auth('admin')->id(),
            ]
        );

        return redirect()
            ->route('admin.settings.branding')
            ->with('success', 'Hero background updated successfully.');
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
