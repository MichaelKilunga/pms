<?php

namespace App\Http\Controllers;

use App\Models\User;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    public function index()
    {
        $audits = Audit::latest()
        ->OrderBy( 'created_at', 'desc')
            ->paginate(10);
        // dd($audits);
        return view('audits.index', compact('audits'));
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
