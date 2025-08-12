<<<<<<< HEAD
# Kilang Pertamina Internasional Refinery Unit VI Balongan - CCTV Monitoring System

A comprehensive CCTV monitoring system for Kilang Pertamina Internasional Refinery Unit VI Balongan, featuring both user and admin interfaces with real-time video streaming capabilities.

## 🏗️ System Architecture

- **Backend**: Laravel 11 (PHP)
- **Frontend**: Vue.js 3 with Inertia.js
- **Database**: SQLite (development) / MySQL (production)
- **Video Streaming**: FFmpeg (RTSP to HLS conversion)
- **Maps**: Leaflet.js with OpenStreetMap and Satellite views
- **Authentication**: Laravel Breeze with Google OAuth support

## 🚀 Features

### User Interface
- **Welcome Page**: Branded landing page with login/register
- **Authentication**: Email/password + Google OAuth integration
- **Dashboard**: Real-time statistics (buildings, rooms, CCTV status)
- **Interactive Maps**: OpenStreetMap/Satellite with colored CCTV markers
- **Location Navigation**: Building → Room → CCTV hierarchy
- **Live CCTV Streaming**: HLS.js powered video playback
- **Contact Information**: Clickable contact details
- **Chat System**: Real-time messaging with admin
- **Settings**: Theme modes, profile management

### Admin Panel
- **CRUD Operations**: Full management of buildings, rooms, cameras, contacts
- **Analytics Dashboard**: Downloadable Excel reports
- **User Management**: Complete user data management
- **CCTV Management**: Add/edit 700+ cameras with IP ranges
- **Message Center**: Admin-user communication
- **System Settings**: Comprehensive configuration options

## 📋 Prerequisites

- PHP 8.2+
- Node.js 18+
- Composer
- FFmpeg (for video streaming)
- Git

## 🛠️ Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd kpi
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   # Create SQLite database (development)
   touch database/database.sqlite
   
   # Run migrations and seed data
   php artisan migrate --seed --force
   ```

6. **Build frontend assets**
   ```bash
   npm run build
   ```

## 🚀 Running the Application

### Development Mode

1. **Start Laravel development server**
   ```bash
   php artisan serve
   ```

2. **Start Vite development server**
   ```bash
   npm run dev
   ```

3. **Start CCTV streams** (optional - for live video)
   ```bash
   # Windows
   start_streams.bat
   
   # Or manually with FFmpeg
   ffmpeg -i rtsp://admin:password.123@10.56.236.01/streaming/channels/101 -c:v copy -c:a aac -f hls -hls_time 2 -hls_list_size 3 -hls_flags delete_segments -hls_segment_filename public/hls/camera_1/segment_%03d.ts public/hls/camera_1/playlist.m3u8
   ```

### Production Mode

1. **Optimize for production**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   npm run build
   ```

2. **Configure web server** (Apache/Nginx) to point to `public/` directory

## 🔐 Default Credentials

- **Admin**: `admin@pertamina.com` / `admin123`
- **Regular User**: Register through the application

## 📊 Database Structure

### Core Tables
- **users**: User accounts with role-based access
- **buildings**: 18 refinery buildings with coordinates
- **rooms**: Multiple rooms per building
- **cameras**: 700+ CCTV cameras with RTSP URLs
- **contacts**: Contact information (admin, support, emergency)
- **messages**: Chat system messages

### Camera IP Range
- **Base URL**: `rtsp://admin:password.123@10.56.236.XX/streaming/channels/101`
- **Range**: 10.56.236.01 to 10.56.236.700

## 🗺️ Map Configuration

The system uses Leaflet.js with:
- **OpenStreetMap**: Default map view
- **Satellite View**: High-resolution satellite imagery
- **Building Markers**: Blue markers for each building
- **CCTV Markers**: Color-coded by status
  - 🟢 Green: Online
  - 🔴 Red: Offline
  - 🟡 Yellow: Maintenance

## 🎥 Video Streaming

### HLS Streaming Setup
1. **FFmpeg Command Structure**:
   ```bash
   ffmpeg -i [RTSP_URL] -c:v copy -c:a aac -f hls -hls_time 2 -hls_list_size 3 -hls_flags delete_segments -hls_segment_filename [OUTPUT_PATH]/segment_%03d.ts [OUTPUT_PATH]/playlist.m3u8
   ```

2. **HLS Directory Structure**:
   ```
   public/hls/
   ├── camera_1/
   │   ├── playlist.m3u8
   │   └── segment_001.ts
   ├── camera_2/
   │   ├── playlist.m3u8
   │   └── segment_001.ts
   └── ...
   ```

### Stream Management
- **Automatic**: Use Laravel Artisan command `php artisan stream:rtsp-to-hls`
- **Manual**: Run individual FFmpeg commands
- **Batch**: Use provided `start_streams.bat` script

## 🔧 Configuration

### Environment Variables
```env
APP_NAME="Kilang Pertamina Internasional"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### Google OAuth Setup
1. Create Google Cloud Project
2. Enable Google+ API
3. Create OAuth 2.0 credentials
4. Add authorized redirect URIs
5. Update `.env` with credentials

## 📱 User Interface Features

### Theme Modes
- **Light Mode**: Clean, bright interface
- **Night Mode**: Dark theme for low-light environments
- **System Mode**: Automatically follows OS preference

### Search Functionality
- **Building Search**: Real-time search with autocomplete
- **Quick Navigation**: Direct links to building locations

### Responsive Design
- **Mobile**: Optimized for mobile devices
- **Tablet**: Touch-friendly interface
- **Desktop**: Full-featured desktop experience

## 🔒 Security Features

- **Email Verification**: Required for all new accounts
- **Password Reset**: Secure email-based password recovery
- **Role-Based Access**: Admin and user role separation
- **CSRF Protection**: Built-in Laravel security
- **Input Validation**: Comprehensive form validation
- **SQL Injection Protection**: Eloquent ORM protection

## 📈 Monitoring & Analytics

### Dashboard Statistics
- Total buildings and rooms
- CCTV status breakdown (online/offline/maintenance)
- Real-time system status
- User activity tracking

### Export Capabilities
- **Excel Reports**: Downloadable analytics data
- **Screenshots**: Camera snapshot functionality
- **Video Recording**: Stream recording capabilities

## 🐛 Troubleshooting

### Common Issues

1. **FFmpeg not found**
   ```bash
   # Install FFmpeg
   # Windows: Download from https://ffmpeg.org/download.html
   # Linux: sudo apt install ffmpeg
   # macOS: brew install ffmpeg
   ```

2. **Database connection issues**
   ```bash
   # Check database file exists
   ls -la database/database.sqlite
   
   # Reset database
   php artisan migrate:fresh --seed
   ```

3. **Video streams not loading**
   ```bash
   # Check HLS directory permissions
   chmod -R 755 public/hls
   
   # Verify FFmpeg streams are running
   ps aux | grep ffmpeg
   ```

4. **Google OAuth not working**
   - Verify Google Cloud Console settings
   - Check redirect URI configuration
   - Ensure API is enabled

## 📞 Support

For technical support or questions:
- **Email**: admin@pertamina.com
- **Phone**: +62-234-123456
- **WhatsApp**: +62-812-34567890

## 📄 License

© 2024 Kilang Pertamina Internasional. All rights reserved.

---

**System Version**: 1.0.0  
**Last Updated**: August 2024  
**Maintained by**: Kilang Pertamina Internasional IT Team
=======
# cctv-vue
>>>>>>> 5513d70f0cdf65b5995633c2c3960dde5f626ae3
# cctv-vue-js
# cctv-vue-js
# cctv-vue-js
