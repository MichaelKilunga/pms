<?php

namespace App\Http\Controllers;

use App\Models\User;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = Audit::latest()->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $audits = $query->paginate(15)->withQueryString();
        
        // Get unique user IDs from audits to include deleted users in filters
        $auditUserIds = Audit::distinct()->pluck('user_id')->filter();
        $existingUsers = User::whereIn('id', $auditUserIds)->get()->keyBy('id');
        
        $users = $auditUserIds->map(function($id) use ($existingUsers) {
            return (object) [
                'id' => $id,
                'name' => isset($existingUsers[$id]) ? $existingUsers[$id]->name : "Deleted User (ID: $id)"
            ];
        })->sortBy('name');

        $events = Audit::select('event')->distinct()->pluck('event');

        return view('audits.index', compact('audits', 'users', 'events'));
    }

    public function getImage()
    {
        try {
            $path = public_path('images/logo.png'); // Adjust the path if needed
            $mimeType = mime_content_type($path); // Get the MIME type

            $imageData = base64_encode(file_get_contents($path));
            $base64Image = "data:$mimeType;base64,$imageData"; // Correct format

            return response()->json(['success' => true, 'base64' => $base64Image]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
