<?php

namespace App\Http\Controllers;

use App\Models\Camera;
use Illuminate\Http\Request;
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

    public function snapshot(Camera $camera)
    {
        $cmd = [
            'ffmpeg', '-rtsp_transport', 'tcp', '-y', '-i', $camera->rtsp_url,
            '-frames:v', '1', '-f', 'image2', 'pipe:1'
        ];
        $descriptorSpec = [1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        $process = proc_open($cmd, $descriptorSpec, $pipes);
        if (!is_resource($process)) {
            return Response::noContent(500);
        }
        $imageData = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);

        return Response::make($imageData, 200, ['Content-Type' => 'image/jpeg']);
    }

    public function export(Camera $camera)
    {
        return Response::noContent(501);
    }
}
