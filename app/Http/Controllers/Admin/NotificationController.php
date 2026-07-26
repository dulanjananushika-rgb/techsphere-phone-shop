<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Services\OrderNotificationService;
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
            'status' => ['required', 'in:'.implode(',', array_keys($this->statuses()))],
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

    public function retry(NotificationLog $notification, OrderNotificationService $notifications)
    {
        if ($notification->channel !== 'email' || ! filter_var($notification->recipient, FILTER_VALIDATE_EMAIL)) {
            return back()->withErrors(['notification' => 'Only email notifications can be retried automatically.']);
        }

        $notifications->retry($notification);

        return back()->with('status', 'Notification delivery retried.');
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
