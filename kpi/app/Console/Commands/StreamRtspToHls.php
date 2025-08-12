<?php

namespace App\Console\Commands;

use App\Models\Camera;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class StreamRtspToHls extends Command
{
    protected $signature = 'cctv:stream {cameraId} {--preset=ultrafast} {--segment=1}';

    protected $description = 'Start FFmpeg to restream a camera RTSP to HLS playlist';

    public function handle(): int
    {
        $camera = Camera::findOrFail((int)$this->argument('cameraId'));

        $storagePath = storage_path('app/public/live');
        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0775, true);
        }

        $fileName = str_replace('.', '_', $camera->ip_address).'.m3u8';
        $output = $storagePath.DIRECTORY_SEPARATOR.$fileName;

        $cmd = [
            'ffmpeg', '-rtsp_transport', 'tcp', '-i', $camera->rtsp_url,
            '-c:v', 'libx264', '-preset', $this->option('preset'), '-tune', 'zerolatency', '-f', 'hls',
            '-hls_time', (string)$this->option('segment'), '-hls_list_size', '3', '-hls_flags', 'delete_segments',
            $output,
        ];

        $this->info('Starting FFmpeg: '.implode(' ', $cmd));

        $camera->update(['hls_playlist_path' => 'storage/live/'.$fileName]);

        $process = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        if (is_resource($process)) {
            stream_set_blocking($pipes[1], false);
            stream_set_blocking($pipes[2], false);
            while (true) {
                $status = proc_get_status($process);
                if (!$status['running']) {
                    break;
                }
                usleep(300000);
            }
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);
        }

        return self::SUCCESS;
    }
}
