<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppInfo;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function uploadApk(Request $request)
    {
        $appInfo = AppInfo::firstOrFail();

        $data = $request->validate([
            'apk_file' => 'required|file|mimes:apk,zip|max:204800',
            'apk_version' => 'nullable|string|max:50',
        ], [
            'apk_file.required' => 'File APK wajib diunggah.',
            'apk_file.mimes' => 'File harus berformat APK (.apk).',
            'apk_file.max' => 'Ukuran file maksimal 200 MB.',
        ]);

        // Hapus file lama jika ada
        if ($appInfo->apk_path && Storage::disk('public')->exists($appInfo->apk_path)) {
            Storage::disk('public')->delete($appInfo->apk_path);
        }

        $file = $request->file('apk_file');
        $path = $file->storeAs('apks', 'kaliwedi-v'.now()->format('YmdHis').'.apk', 'public');

        $appInfo->update([
            'apk_path' => $path,
            'apk_filename' => $file->getClientOriginalName(),
            'apk_version' => $data['apk_version'] ?? null,
            'apk_uploaded_at' => now(),
        ]);

        Audit::log('app.apk_uploaded', 'APK aplikasi diunggah (versi '.($data['apk_version'] ?? '-').', '.$file->getClientOriginalName().').', $appInfo);

        return redirect()->route('admin.app-info.edit')
            ->with('success', 'APK berhasil diunggah. Tombol unduh di landing page kini aktif.');
    }

    public function removeApk()
    {
        $appInfo = AppInfo::firstOrFail();

        if ($appInfo->apk_path && Storage::disk('public')->exists($appInfo->apk_path)) {
            Storage::disk('public')->delete($appInfo->apk_path);
        }

        $appInfo->update([
            'apk_path' => null,
            'apk_filename' => null,
            'apk_version' => null,
            'apk_uploaded_at' => null,
        ]);

        Audit::log('app.apk_removed', 'File APK aplikasi dihapus dari sistem.', $appInfo);

        return redirect()->route('admin.app-info.edit')
            ->with('success', 'APK berhasil dihapus. Tombol unduh akan disembunyikan.');
    }
}
