<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $notifications = AdminNotification::latest()->paginate(20);
        return view('admin-views.admin-notification.index', compact('notifications'));
    }

    public function getNotifications()
    {
        $notifications = AdminNotification::where('is_read', false)->latest()->take(10)->get();
        $unreadCount = AdminNotification::where('is_read', false)->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        AdminNotification::where('is_read', false)->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
