<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class MediaController extends Controller
{
    /**
     * Serve media files from whatsapp-attachments
     * 
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public function serveMedia($filename)
    {
        $path = 'whatsapp-attachments/' . $filename;

        // Check if file exists in public storage
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Media file not found');
        }

        // Get file contents
        $file = Storage::disk('public')->get($path);

        // Determine mime type based on extension
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'mp4' => 'video/mp4',
            'avi' => 'video/x-msvideo',
            'mov' => 'video/quicktime',
            'ogg' => 'audio/ogg',
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        $mimeType = $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';

        // Return file with appropriate headers
        return Response::make($file, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
