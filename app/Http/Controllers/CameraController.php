<?php

namespace App\Http\Controllers;

use App\Models\Camera;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class CameraController extends Controller
{
    public function index(): Response
    {
        $cameras = Camera::with(['building', 'room'])
            ->orderBy('name')
            ->paginate(50)
            ->through(function ($camera) {
                return [
                    'id' => $camera->id,
                    'name' => $camera->name,
                    'status' => $camera->status,
                    'building' => $camera->building?->name,
                    'room' => $camera->room?->name,
                    'ip_address' => $camera->ip_address,
                    'hls_url' => $camera->hls_url,
                ];
            });

        return Inertia::render('Admin/Cameras/Index', [
            'cameras' => $cameras,
        ]);
    }

    public function show(Camera $camera)
    {
        $camera->load(['building', 'room']);
        
        // Generate HLS URL if not exists
        if (!$camera->hls_url) {
            $camera->hls_url = "/hls/camera_{$camera->id}/playlist.m3u8";
        }
        
        return Inertia::render('Cameras/Show', [
            'camera' => $camera,
        ]);
    }

    public function create(): Response
    {
        $buildings = Building::orderBy('name')->get();
        $rooms = Room::orderBy('name')->get();

        return Inertia::render('Cameras/Create', [
            'buildings' => $buildings,
            'rooms' => $rooms,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'room_id' => 'nullable|exists:rooms,id',
            'name' => 'required|string|max:255',
            'ip_address' => 'required|string|unique:cameras,ip_address',
            'rtsp_url' => 'nullable|string',
            'status' => 'required|in:online,offline,maintenance',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_public' => 'boolean',
        ]);

        $camera = Camera::create($validated);

        return response()->json([
            'message' => 'Camera created successfully',
            'camera' => $camera->load(['building', 'room'])
        ]);
    }

    public function edit(Camera $camera): Response
    {
        $camera->load(['building', 'room']);
        $buildings = Building::orderBy('name')->get();
        $rooms = Room::orderBy('name')->get();

        return Inertia::render('Cameras/Edit', [
            'camera' => $camera,
            'buildings' => $buildings,
            'rooms' => $rooms,
        ]);
    }

    public function update(Request $request, Camera $camera): JsonResponse
    {
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'room_id' => 'nullable|exists:rooms,id',
            'name' => 'required|string|max:255',
            'ip_address' => 'required|string|unique:cameras,ip_address,' . $camera->id,
            'rtsp_url' => 'nullable|string',
            'status' => 'required|in:online,offline,maintenance',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_public' => 'boolean',
        ]);

        $camera->update($validated);

        return response()->json([
            'message' => 'Camera updated successfully',
            'camera' => $camera->load(['building', 'room'])
        ]);
    }

    public function destroy(Camera $camera): JsonResponse
    {
        $camera->delete();

        return response()->json([
            'message' => 'Camera deleted successfully'
        ]);
    }

    public function updateStatus(Request $request, Camera $camera): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:online,offline,maintenance'
        ]);

        $camera->update($validated);

        return response()->json([
            'message' => 'Camera status updated successfully',
            'camera' => $camera->load(['building', 'room'])
        ]);
    }

    public function getStreamUrl(Camera $camera): JsonResponse
    {
        return response()->json([
            'rtsp_url' => $camera->rtsp_url,
            'hls_url' => $camera->hls_url,
        ]);
    }

    public function getByBuilding(Building $building): JsonResponse
    {
        $cameras = $building->cameras()
            ->with(['room'])
            ->get();

        return response()->json([
            'cameras' => $cameras,
            'stats' => [
                'total' => $cameras->count(),
                'online' => $cameras->where('status', 'online')->count(),
                'offline' => $cameras->where('status', 'offline')->count(),
                'maintenance' => $cameras->where('status', 'maintenance')->count(),
            ]
        ]);
    }

    public function getByRoom(Room $room): JsonResponse
    {
        $cameras = $room->cameras()->get();

        return response()->json([
            'cameras' => $cameras,
            'stats' => [
                'total' => $cameras->count(),
                'online' => $cameras->where('status', 'online')->count(),
                'offline' => $cameras->where('status', 'offline')->count(),
                'maintenance' => $cameras->where('status', 'maintenance')->count(),
            ]
        ]);
    }

    // --- Admin utilities ---

    public function startStream(Camera $camera): JsonResponse
    {
        if ($camera->status !== 'online') {
            return response()->json(['message' => 'Camera is not online'], 422);
        }

        $outputDir = public_path("hls/camera_{$camera->id}");
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $command = [
            'ffmpeg',
            '-rtsp_transport', 'tcp',
            '-i', $camera->rtsp_url,
            '-c:v', 'libx264',
            '-preset', 'ultrafast',
            '-tune', 'zerolatency',
            '-f', 'hls',
            '-hls_time', '1',
            '-hls_list_size', '3',
            '-hls_flags', 'delete_segments',
            '-hls_segment_filename', "$outputDir/segment_%03d.ts",
            "$outputDir/playlist.m3u8",
        ];

        $process = new Process($command);
        $process->setTimeout(0);
        $process->disableOutput();
        $process->start();

        return response()->json(['message' => 'Stream starting']);
    }

    public function stopStream(Camera $camera): JsonResponse
    {
        // Try to kill ffmpeg for this camera (best-effort, platform dependent)
        $pattern = "camera_{$camera->id}";
        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            // Windows: use wmic to find and kill processes containing the output path
            @pclose(popen("wmic process where \"CommandLine like '%$pattern%' and Name='ffmpeg.exe'\" call terminate", 'r'));
        } else {
            $proc = new Process(['pkill', '-f', $pattern]);
            $proc->run();
        }

        return response()->json(['message' => 'Stream stop signal sent']);
    }

    public function snapshot(Camera $camera): JsonResponse
    {
        $dir = 'public/captures/camera_' . $camera->id;
        Storage::makeDirectory($dir);

        $filename = now()->format('Ymd_His') . '.jpg';
        $fullPath = storage_path('app/' . $dir . '/' . $filename);

        $command = [
            'ffmpeg', '-y', '-rtsp_transport', 'tcp', '-i', $camera->rtsp_url,
            '-frames:v', '1', $fullPath
        ];

        $process = new Process($command);
        $process->setTimeout(60);
        $process->run();

        if (!$process->isSuccessful()) {
            return response()->json(['message' => 'Failed to capture snapshot'], 500);
        }

        return response()->json([
            'message' => 'Snapshot captured',
            'url' => Storage::url('captures/camera_' . $camera->id . '/' . $filename),
        ]);
    }

    public function record(Request $request, Camera $camera): JsonResponse
    {
        $seconds = (int) ($request->input('seconds', 10));
        $seconds = max(1, min($seconds, 300)); // 1..300 seconds

        $dir = 'public/recordings/camera_' . $camera->id;
        Storage::makeDirectory($dir);

        $filename = now()->format('Ymd_His') . '.mp4';
        $fullPath = storage_path('app/' . $dir . '/' . $filename);

        $command = [
            'ffmpeg', '-y', '-rtsp_transport', 'tcp', '-i', $camera->rtsp_url,
            '-t', (string)$seconds, '-c:v', 'copy', '-c:a', 'aac', $fullPath
        ];

        $process = new Process($command);
        $process->setTimeout($seconds + 30);
        $process->run();

        if (!$process->isSuccessful()) {
            return response()->json(['message' => 'Failed to record video'], 500);
        }

        return response()->json([
            'message' => 'Recording completed',
            'url' => Storage::url('recordings/camera_' . $camera->id . '/' . $filename),
        ]);
    }
}
