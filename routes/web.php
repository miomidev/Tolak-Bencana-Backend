<?php

use App\Http\Controllers\Admin\AlertLevelController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\AppInfoController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\BroadcastController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DisasterZoneController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\HelpContactController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReportCategoryController;
use App\Http\Controllers\Admin\ReportCommentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ShelterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Public Web Routes (HomeController)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/download-apk', [HomeController::class, 'downloadApk'])->name('download.apk');
Route::get('/report/{report}', [HomeController::class, 'showReport'])->name('report.show');

// Admin Auth Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post')->middleware('throttle:admin-login');

Route::get('/admin/forgot-password', [AdminAuthController::class, 'showForgotPasswordForm'])->name('admin.forgot-password');
Route::post('/admin/forgot-password', [AdminAuthController::class, 'sendResetOtp'])->name('admin.forgot-password.post')->middleware('throttle:admin-login');

Route::get('/admin/forgot-password/otp', [AdminAuthController::class, 'showOtpForm'])->name('admin.forgot-password.otp');
Route::post('/admin/forgot-password/otp', [AdminAuthController::class, 'verifyOtp'])->name('admin.forgot-password.otp.post')->middleware('throttle:admin-login');

Route::get('/admin/reset-password', [AdminAuthController::class, 'showResetPasswordForm'])->name('admin.reset-password');
Route::post('/admin/reset-password', [AdminAuthController::class, 'resetPassword'])->name('admin.reset-password.post')->middleware('throttle:admin-login');

// Protected Admin Routes
Route::middleware([AdminMiddleware::class])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Master Data: Shelter Evakuasi
    Route::get('/admin/shelters', [ShelterController::class, 'index'])->name('admin.shelters.index');
    Route::get('/admin/shelters/create', [ShelterController::class, 'create'])->name('admin.shelters.create');
    Route::post('/admin/shelters', [ShelterController::class, 'store'])->name('admin.shelters.store');
    Route::get('/admin/shelters/{shelter}/edit', [ShelterController::class, 'edit'])->name('admin.shelters.edit');
    Route::put('/admin/shelters/{shelter}', [ShelterController::class, 'update'])->name('admin.shelters.update');
    Route::delete('/admin/shelters/{shelter}', [ShelterController::class, 'destroy'])->name('admin.shelters.destroy');

    // Master Data: Zona Rawan Banjir
    Route::get('/admin/zones', [DisasterZoneController::class, 'index'])->name('admin.zones.index');
    Route::get('/admin/zones/create', [DisasterZoneController::class, 'create'])->name('admin.zones.create');
    Route::post('/admin/zones', [DisasterZoneController::class, 'store'])->name('admin.zones.store');
    Route::get('/admin/zones/{zone}/edit', [DisasterZoneController::class, 'edit'])->name('admin.zones.edit');
    Route::put('/admin/zones/{zone}', [DisasterZoneController::class, 'update'])->name('admin.zones.update');
    Route::delete('/admin/zones/{zone}', [DisasterZoneController::class, 'destroy'])->name('admin.zones.destroy');

    // Master Data: Kontak Darurat
    Route::get('/admin/contacts', [HelpContactController::class, 'index'])->name('admin.contacts.index');
    Route::get('/admin/contacts/create', [HelpContactController::class, 'create'])->name('admin.contacts.create');
    Route::post('/admin/contacts', [HelpContactController::class, 'store'])->name('admin.contacts.store');
    Route::get('/admin/contacts/{contact}/edit', [HelpContactController::class, 'edit'])->name('admin.contacts.edit');
    Route::put('/admin/contacts/{contact}', [HelpContactController::class, 'update'])->name('admin.contacts.update');
    Route::delete('/admin/contacts/{contact}', [HelpContactController::class, 'destroy'])->name('admin.contacts.destroy');
    Route::post('/admin/contacts/reorder', [HelpContactController::class, 'reorder'])->name('admin.contacts.reorder');
    Route::post('/admin/contacts/{contact}/toggle', [HelpContactController::class, 'toggle'])->name('admin.contacts.toggle');

    // Master Data: FAQ
    Route::get('/admin/faqs', [FaqController::class, 'index'])->name('admin.faqs.index');
    Route::get('/admin/faqs/create', [FaqController::class, 'create'])->name('admin.faqs.create');
    Route::post('/admin/faqs', [FaqController::class, 'store'])->name('admin.faqs.store');
    Route::get('/admin/faqs/{faq}/edit', [FaqController::class, 'edit'])->name('admin.faqs.edit');
    Route::put('/admin/faqs/{faq}', [FaqController::class, 'update'])->name('admin.faqs.update');
    Route::delete('/admin/faqs/{faq}', [FaqController::class, 'destroy'])->name('admin.faqs.destroy');
    Route::post('/admin/faqs/{faq}/toggle', [FaqController::class, 'toggle'])->name('admin.faqs.toggle');

    // Konfigurasi Aplikasi (single row)
    Route::get('/admin/app-info', [AppInfoController::class, 'edit'])->name('admin.app-info.edit');
    Route::put('/admin/app-info', [AppInfoController::class, 'update'])->name('admin.app-info.update');
    Route::post('/admin/app-info/apk', [AppInfoController::class, 'uploadApk'])->name('admin.app-info.apk.upload');
    Route::post('/admin/app-info/apk/remove', [AppInfoController::class, 'removeApk'])->name('admin.app-info.apk.remove');

    // Master Data: Kategori Laporan
    Route::get('/admin/categories', [ReportCategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/admin/categories/create', [ReportCategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/admin/categories', [ReportCategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/admin/categories/{category}/edit', [ReportCategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/admin/categories/{category}', [ReportCategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [ReportCategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::post('/admin/categories/{category}/toggle', [ReportCategoryController::class, 'toggle'])->name('admin.categories.toggle');

    // Master Data: Level Siaga
    Route::get('/admin/alert-levels', [AlertLevelController::class, 'index'])->name('admin.alert-levels.index');
    Route::get('/admin/alert-levels/create', [AlertLevelController::class, 'create'])->name('admin.alert-levels.create');
    Route::post('/admin/alert-levels', [AlertLevelController::class, 'store'])->name('admin.alert-levels.store');
    Route::get('/admin/alert-levels/{level}/edit', [AlertLevelController::class, 'edit'])->name('admin.alert-levels.edit');
    Route::put('/admin/alert-levels/{level}', [AlertLevelController::class, 'update'])->name('admin.alert-levels.update');
    Route::delete('/admin/alert-levels/{level}', [AlertLevelController::class, 'destroy'])->name('admin.alert-levels.destroy');
    Route::post('/admin/alert-levels/{level}/toggle', [AlertLevelController::class, 'toggle'])->name('admin.alert-levels.toggle');

    // Pemberitahuan (Running Text Landing Page)
    Route::get('/admin/announcements', [AnnouncementController::class, 'index'])->name('admin.announcements.index');
    Route::get('/admin/announcements/create', [AnnouncementController::class, 'create'])->name('admin.announcements.create');
    Route::post('/admin/announcements', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
    Route::get('/admin/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('admin.announcements.edit');
    Route::put('/admin/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('admin.announcements.update');
    Route::delete('/admin/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');
    Route::post('/admin/announcements/{announcement}/toggle', [AnnouncementController::class, 'toggle'])->name('admin.announcements.toggle');

    // Manajemen Pengguna
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::post('/admin/users/{user}/toggle', [UserController::class, 'toggle'])->name('admin.users.toggle');
    Route::post('/admin/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('admin.users.reset-password');
    Route::post('/admin/users/{user}/notifications', [UserController::class, 'updateNotifications'])->name('admin.users.notifications');

    // Profil Admin
    Route::get('/admin/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/admin/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::put('/admin/profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password');

    // Broadcast Siaga
    Route::get('/admin/broadcast/create', [BroadcastController::class, 'create'])->name('admin.broadcast.create');
    Route::post('/admin/broadcast/send', [BroadcastController::class, 'send'])->name('admin.broadcast.send');
    Route::get('/admin/broadcast/history', [BroadcastController::class, 'history'])->name('admin.broadcast.history');

    // Moderasi Komentar
    Route::get('/admin/comments', [ReportCommentController::class, 'index'])->name('admin.comments.index');
    Route::get('/admin/comments/{comment}', [ReportCommentController::class, 'show'])->name('admin.comments.show');
    Route::post('/admin/comments/{comment}/toggle', [ReportCommentController::class, 'toggle'])->name('admin.comments.toggle');
    Route::delete('/admin/comments/{comment}', [ReportCommentController::class, 'destroy'])->name('admin.comments.destroy');

    // Audit Log
    Route::get('/admin/audit-logs', [AuditLogController::class, 'index'])->name('admin.audit-logs.index');

    // Moderasi Laporan
    Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/admin/reports/{report}', [ReportController::class, 'show'])->name('admin.reports.show');
    Route::post('/admin/reports/{report}/status', [ReportController::class, 'updateStatus'])->name('admin.reports.status');
    Route::get('/admin/reports/export/excel', [ReportController::class, 'exportExcel'])->name('admin.reports.export');
});
