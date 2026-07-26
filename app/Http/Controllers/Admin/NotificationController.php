<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return view('admin.notifications.index', [
            'notifications' => NotificationLog::with('order')->latest()->paginate(20),
            'statuses' => $this->statuses(),
        ]);
    }

    public function update(Request $request, NotificationLog $notification)
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys($this->statuses()))],
        ]);

        $notification->update([
            'status' => $data['status'],
            'sent_at' => $data['status'] === 'sent' ? ($notification->sent_at ?? now()) : null,
        ]);

        return back()->with('status', 'Notification updated.');
    }

    public function destroy(NotificationLog $notification)
    {
        $notification->delete();

        return back()->with('status', 'Notification deleted.');
    }

    private function statuses(): array
    {
        return [
            'queued' => 'Queued',
            'sent' => 'Sent',
            'failed' => 'Failed',
        ];
    }
}
