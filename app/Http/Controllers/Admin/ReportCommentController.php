<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportComment;
use App\Support\Audit;
use Illuminate\Http\Request;

class ReportCommentController extends Controller
{
    public function index(Request $request)
    {
        $query = ReportComment::query()->with(['user:id,name,email,role,is_active', 'report:id,address']);

        if ($q = $request->input('q')) {
            $query->where('comment', 'like', '%'.$q.'%');
        }

        if ($request->filled('status') && in_array($request->status, ['active', 'hidden'])) {
            $query->where('is_active', $request->status === 'active');
        }

        $comments = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.comments.index', compact('comments'));
    }

    public function show(ReportComment $comment)
    {
        $comment->load(['user', 'report:id,address,status,severity']);

        return view('admin.comments.show', compact('comment'));
    }

    public function toggle(ReportComment $comment)
    {
        $comment->is_active = ! $comment->is_active;
        $comment->save();

        Audit::log(
            $comment->is_active ? 'comment.shown' : 'comment.hidden',
            'Komentar pada laporan "'.$comment->report?->address.'" '.($comment->is_active ? 'ditampilkan kembali' : 'disembunyikan').'.',
            $comment
        );

        return redirect()->route('admin.comments.show', $comment)
            ->with('success', 'Komentar '.($comment->is_active ? 'ditampilkan kembali' : 'berhasil disembunyikan').'.');
    }

    public function destroy(ReportComment $comment)
    {
        $comment->delete();

        Audit::log('comment.deleted', 'Komentar pada laporan "'.$comment->report?->address.'" dihapus.', $comment);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Komentar berhasil dihapus.');
    }
}
