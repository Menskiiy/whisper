<?php
namespace App\Http\Controllers;
use App\Models\Notification;

class NotificationController extends Controller {
    public function index() {
        $notifications = Notification::where('user_id', auth()->id())
            ->with('actor')
            ->latest()
            ->paginate(30);
        Notification::where('user_id', auth()->id())->where('is_read', false)->update(['is_read' => true]);
        return view('notifications', compact('notifications'));
    }
}
