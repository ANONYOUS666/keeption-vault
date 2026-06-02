<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public static function log($ownerId, $userId, $userName, $action, $details, Request $request = null)
    {
        $ip = $request ? $request->ip() : request()->ip();
        $device = $request ? $request->header('User-Agent') : request()->header('User-Agent');

        // Clean user agent for display
        if ($device) {
            if (preg_match('/(Windows|Macintosh|Linux|Android|iPhone|iPad)/i', $device, $matches)) {
                $device = $matches[1];
            } else {
                $device = 'Other';
            }
        } else {
            $device = 'Unknown';
        }

        return AuditLog::create([
            'owner_id'   => $ownerId,
            'user_id'    => $userId,
            'user_name'  => $userName,
            'action'     => $action,
            'details'    => $details,
            'ip_address' => $ip,
            'device'     => $device,
        ]);
    }

    public function index(Request $request)
    {
        $ownerId = session('user_id');
        if (!$ownerId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $query = AuditLog::where('owner_id', $ownerId)->orderBy('created_at', 'desc');

        if ($request->filled('member')) {
            $query->where(function($q) use ($request) {
                $q->where('user_name', 'like', '%' . $request->member . '%')
                  ->orWhere('user_id', $request->member);
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('details', 'like', '%' . $search . '%')
                  ->orWhere('user_name', 'like', '%' . $search . '%');
            });
        }

        $logs = $query->get()->map(function($log) {
            return [
                'id'         => $log->id,
                'user_name'  => $log->user_name,
                'action'     => $log->action,
                'details'    => $log->details,
                'date'       => $log->created_at->format('M d, Y h:i A'),
                'device'     => $log->device,
                'ip'         => $log->ip_address,
            ];
        });

        return response()->json($logs);
    }

    public function export()
    {
        $ownerId = session('user_id');
        if (!$ownerId) {
            return redirect('/login');
        }

        $logs = AuditLog::where('owner_id', $ownerId)->orderBy('created_at', 'desc')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=keeption_audit_trail_" . date('Ymd') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'User', 'Action', 'Details', 'Date & Time', 'IP Address', 'Device'];

        $callback = function() use ($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user_name,
                    ucfirst($log->action),
                    $log->details,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->ip_address,
                    $log->device,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
