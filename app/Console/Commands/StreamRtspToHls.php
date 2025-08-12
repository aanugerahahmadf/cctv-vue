<?php

namespace App\Console\Commands;

use App\Models\Camera;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class StreamRtspToHls extends Command
{
    protected $signature = 'stream:rtsp-to-hls {camera_id?} {--all}';
    protected $description = 'Convert RTSP streams to HLS for web viewing';

    public function handle()
    {
        if ($this->option('all')) {
            $this->streamAllCameras();
        } elseif ($cameraId = $this->argument('camera_id')) {
            $this->streamCamera($cameraId);
        } else {
            $this->error('Please specify a camera ID or use --all option');
            return 1;
        }

        return 0;
    }

    private function streamAllCameras()
    {
        $cameras = Camera::where('status', 'online')->get();
        
        $this->info("Starting streams for {$cameras->count()} cameras...");
        
        foreach ($cameras as $camera) {
            $this->streamCamera($camera->id);
        }
    }

    private function streamCamera($cameraId)
    {
        $camera = Camera::find($cameraId);
        
        if (!$camera) {
            $this->error("Camera with ID {$cameraId} not found");
            return;
        }

        if ($camera->status !== 'online') {
            $this->warn("Camera {$camera->name} is not online, skipping...");
            return;
        }

        $this->info("Starting stream for camera: {$camera->name}");

        // Create HLS output directory
        $outputDir = public_path("hls/camera_{$camera->id}");
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // FFmpeg command to convert RTSP to HLS
        $command = [
            'ffmpeg',
            '-i', $camera->rtsp_url,
            '-c:v', 'copy',
            '-c:a', 'aac',
            '-f', 'hls',
            '-hls_time', '2',
            '-hls_list_size', '10',
            '-hls_flags', 'delete_segments',
            '-hls_segment_filename', "{$outputDir}/segment_%03d.ts",
            "{$outputDir}/playlist.m3u8"
        ];

        $process = new Process($command);
        $process->setTimeout(0); // No timeout for continuous streaming

        $process->run(function ($type, $buffer) use ($camera) {
            if (Process::ERR === $type) {
                Log::error("FFmpeg error for camera {$camera->name}: {$buffer}");
            } else {
                Log::info("FFmpeg output for camera {$camera->name}: {$buffer}");
            }
        });

        if (!$process->isSuccessful()) {
            $this->error("Failed to start stream for camera {$camera->name}");
            Log::error("FFmpeg process failed for camera {$camera->name}: " . $process->getErrorOutput());
        } else {
            $this->info("Stream started successfully for camera {$camera->name}");
        }
    }

    public function stopStream($cameraId)
    {
        // This method can be used to stop a specific camera stream
        $camera = Camera::find($cameraId);
        
        if (!$camera) {
            return false;
        }

        // Kill FFmpeg process for this camera
        $process = new Process(['pkill', '-f', "camera_{$camera->id}"]);
        $process->run();

        $this->info("Stream stopped for camera {$camera->name}");
        return true;
    }
}
