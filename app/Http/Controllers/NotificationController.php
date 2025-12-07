<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
   /**
    * Show all notifications page
    */
   public function showAll(): View
   {
      return view('pages.notifications.index');
   }

   /**
    * Get notifications for current user (API)
    */
   public function index(Request $request): JsonResponse
   {
      $user = Auth::user();
      $perPage = 20;

      $notifications = $user->notifications()
         ->with('fromUser')
         ->orderBy('created_at', 'desc')
         ->paginate($perPage);

      $unreadCount = $user->unread_notifications_count;
      $total = $user->notifications()->count();

      // Transform notifications to include icon and bg_color
      $transformedNotifications = collect($notifications->items())->map(function ($notif) {
         return [
            'id' => $notif->id,
            'type' => $notif->type,
            'title' => $notif->title,
            'message' => $notif->message,
            'link' => $notif->link,
            'read_at' => $notif->read_at,
            'created_at' => $notif->created_at,
            'from_user' => $notif->fromUser,
            'icon' => $this->getNotificationIcon($notif->type),
            'bg_color' => $this->getNotificationBgColor($notif->type),
         ];
      });

      return response()->json([
         'success' => true,
         'notifications' => $transformedNotifications,
         'unread_count' => $unreadCount,
         'total' => $total,
         'current_page' => $notifications->currentPage(),
         'last_page' => $notifications->lastPage(),
         'per_page' => $perPage
      ]);
   }

   /**
    * Get icon class based on notification type
    */
   private function getNotificationIcon(string $type): string
   {
      return match ($type) {
         'laporan_baru', 'laporan_pending' => 'fa-file-invoice-dollar text-blue-500',
         'laporan_approved' => 'fa-check-circle text-green-500',
         'laporan_rejected' => 'fa-times-circle text-red-500',
         'informasi' => 'fa-bullhorn text-orange-500',
         default => 'fa-bell text-slate-500',
      };
   }

   /**
    * Get background color class based on notification type
    */
   private function getNotificationBgColor(string $type): string
   {
      return match ($type) {
         'laporan_baru', 'laporan_pending' => 'bg-blue-100',
         'laporan_approved' => 'bg-green-100',
         'laporan_rejected' => 'bg-red-100',
         'informasi' => 'bg-orange-100',
         default => 'bg-slate-100',
      };
   }

   /**
    * Mark notification as read
    */
   public function markAsRead(Request $request, $id): JsonResponse
   {
      $notification = Notification::where('user_id', Auth::id())
         ->findOrFail($id);

      $notification->markAsRead();

      return response()->json([
         'success' => true,
         'message' => 'Notifikasi telah dibaca'
      ]);
   }

   /**
    * Mark all notifications as read
    */
   public function markAllAsRead(): JsonResponse
   {
      Notification::where('user_id', Auth::id())
         ->whereNull('read_at')
         ->update(['read_at' => now()]);

      return response()->json([
         'success' => true,
         'message' => 'Semua notifikasi telah dibaca'
      ]);
   }

   /**
    * Delete a notification
    */
   public function destroy($id): JsonResponse
   {
      $notification = Notification::where('user_id', Auth::id())
         ->findOrFail($id);

      $notification->delete();

      return response()->json([
         'success' => true,
         'message' => 'Notifikasi telah dihapus'
      ]);
   }

   /**
    * Get unread count
    */
   public function unreadCount(): JsonResponse
   {
      $count = Auth::user()->unread_notifications_count;

      return response()->json([
         'success' => true,
         'count' => $count
      ]);
   }
}
