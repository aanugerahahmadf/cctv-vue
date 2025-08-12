@echo off
echo Starting CCTV Streams for Kilang Pertamina Internasional...
echo.

REM Create HLS directory if it doesn't exist
if not exist "public\hls" mkdir "public\hls"

echo Starting streams for cameras 1-700...
echo.

REM Loop through cameras 1-700
for /l %%i in (1,1,700) do (
    REM Create camera directory
    if not exist "public\hls\camera_%%i" mkdir "public\hls\camera_%%i"
    
    REM Format IP suffix (01, 02, ..., 99, 100, ..., 700)
    if %%i lss 100 (
        set "suffix=0%%i"
    ) else (
        set "suffix=%%i"
    )
    
    REM Start FFmpeg stream for this camera
    start "Camera %%i" cmd /c "ffmpeg -rtsp_transport tcp -i rtsp://admin:password.123@10.56.236.!suffix!/streaming/channels/101 -c:v libx264 -preset ultrafast -tune zerolatency -f hls -hls_time 1 -hls_list_size 3 -hls_flags delete_segments -hls_segment_filename public/hls/camera_%%i/segment_%%03d.ts public/hls/camera_%%i/playlist.m3u8"
    
    REM Small delay to prevent overwhelming the system
    timeout /t 1 /nobreak >nul
)

echo.
echo All 700 camera streams started!
echo Access the application at: http://localhost:8000
echo.
echo Note: Make sure FFmpeg is installed and available in your PATH
echo If streams fail to connect, the cameras may be offline or IP addresses may be different
echo.
echo To stop all streams, run: taskkill /f /im ffmpeg.exe
pause
