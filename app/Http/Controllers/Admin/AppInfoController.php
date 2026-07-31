<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppInfo;
use Illuminate\Http\Request;

class AppInfoController extends Controller
{
    public function edit()
    {
        $appInfo = AppInfo::first();

        if (! $appInfo) {
            $appInfo = AppInfo::create([
                'app_name' => 'Kaliwedi',
                'developer' => 'KALIWEDI PKM-KC',
            ]);
        }

        return view('admin.app-info.edit', compact('appInfo'));
    }

    public function update(Request $request)
    {
        $appInfo = AppInfo::firstOrFail();

        $data = $request->validate([
            'app_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'developer' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'support_email' => 'nullable|email|max:255',
            'privacy_policy_url' => 'nullable|url|max:255',
            'terms_url' => 'nullable|url|max:255',
        ], [
            'app_name.required' => 'Nama aplikasi wajib diisi.',
            'developer.required' => 'Nama developer wajib diisi.',
            'website.url' => 'Format website tidak valid.',
            'support_email.email' => 'Format email tidak valid.',
            'privacy_policy_url.url' => 'Format URL tidak valid.',
            'terms_url.url' => 'Format URL tidak valid.',
        ]);

        $appInfo->update($data);

        return redirect()->route('admin.app-info.edit')
            ->with('success', 'Konfigurasi aplikasi berhasil diperbarui.');
    }
}
