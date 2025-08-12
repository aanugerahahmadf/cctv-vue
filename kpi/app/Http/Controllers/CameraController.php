<?php

namespace App\Http\Controllers;

use App\Models\Camera;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class CameraController extends Controller
{
    public function live(Camera $camera): InertiaResponse
    {
        return Inertia::render('Camera/Live', [
            'camera' => [
                'id' => $camera->id,
                'name' => $camera->name,
                'status' => $camera->status,
                'hls' => $camera->hls_playlist_path,
                'rtsp' => $camera->rtsp_url,
            ],
        ]);
    }

    public function snapshot(Camera $camera): HttpResponse
    {
        // For production: run ffmpeg -y -i rtsp -frames:v 1 -f image2 pipe:1
        return Response::noContent(501);
    }

    public function export(Camera $camera)
    {
        // For production: stream HLS segments into a file; placeholder
        return Response::noContent(501);
    }
}
