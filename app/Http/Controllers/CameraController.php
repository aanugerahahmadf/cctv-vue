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
    public function index(Request $request): Response
    {
        $q = $request->string('q')->toString();
        $status = $request->string('status')->toString();
        $buildingId = $request->integer('building_id');
        $sort = $request->string('sort')->toString() ?: 'name';
        $direction = $request->string('direction')->toString() ?: 'asc';
        $perPage = max(10, min(200, (int) $request->input('perPage', 50)));

        $query = Camera::query()->with(['building', 'room']);

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('ip_address', 'like', "%{$q}%")
                  ->orWhereHas('building', fn($b) => $b->where('name', 'like', "%{$q}%"))
                  ->orWhereHas('room', fn($r) => $r->where('name', 'like', "%{$q}%"));
            });
        }

        if (in_array($status, ['online','offline','maintenance'], true)) {
            $query->where('status', $status);
        }

        if ($buildingId) {
            $query->where('building_id', $buildingId);
        }

        if (! in_array($sort, ['name','status','ip_address','id'], true)) {
            $sort = 'name';
        }
        if (! in_array(strtolower($direction), ['asc','desc'], true)) {
            $direction = 'asc';
        }

        $cameras = $query
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString()
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

        $buildings = Building::orderBy('name')->get(['id','name']);

        return Inertia::render('Admin/Cameras/Index', [
            'cameras' => $cameras,
            'filters' => [
                'q' => $q,
                'status' => $status,
                'building_id' => $buildingId,
                'sort' => $sort,
                'direction' => $direction,
                'perPage' => $perPage,
            ],
            'buildings' => $buildings,
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
        $dir = public_path("hls/camera_{$camera->id}");
        if (file_exists($dir)) {
            collect(glob("{$dir}/*"))->each(fn($f) => @unlink($f));
            @rmdir($dir);
        }

        return response()->json(['message' => 'Stream stopped']);
    }

    public function snapshot(Camera $camera): JsonResponse
    {
        $outputDir = public_path('snapshots');
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $file = $outputDir.'/camera_'.$camera->id.'_'.time().'.jpg';
        $process = new Process(['ffmpeg', '-y', '-i', $camera->rtsp_url, '-vframes', '1', $file]);
        $process->run();

        return response()->json(['message' => 'Snapshot saved', 'file' => url(str_replace(public_path(), '', $file))]);
    }

    public function record(Request $request, Camera $camera): JsonResponse
    {
        $seconds = (int) $request->input('seconds', 10);
        $outputDir = public_path('recordings');
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $file = $outputDir.'/camera_'.$camera->id.'_'.time().'.mp4';
        $process = new Process(['ffmpeg', '-y', '-i', $camera->rtsp_url, '-t', (string) $seconds, '-c', 'copy', $file]);
        $process->run();

        return response()->json(['message' => 'Recording saved', 'file' => url(str_replace(public_path(), '', $file))]);
    }
}
