# Dynamic Image Generator with Admin Interface

A powerful PHP-based image generator that adds personalized text overlays to images - perfect for email personalization. Includes a drag-and-drop admin interface for easy configuration.

## Features

- **Web-based Admin Interface** - Upload images, drag text to position, customize fonts
- **Drag & Drop Text Positioning** - Interactive canvas for precise text placement
- **Font Customization** - Multiple font families, sizes, weights, and colors
- **Real-time Preview** - See changes instantly
- **Configuration Management** - Save and load settings per image
- **Server-side Storage** - Configurations saved to JSON files
- **Client-side Fallback** - Works with localStorage if server unavailable
- **Automatic Image Upload** - Upload and assign image IDs automatically
- **TrueType Font Support** - Better rendering with .ttf fonts
- **Responsive Design** - Works on desktop and mobile devices

## Usage

### Admin Interface

Open the admin panel:
```
https://yourdomain.com/img/admin.php
```

1. **Upload an image** - Click the upload area to select an image file
2. **Enter preview text** - Type your personalized text
3. **Customize fonts** - Adjust font family, size, weight, and colors
4. **Drag text to position** - Click and drag the text anywhere on the image
5. **Save configuration** - Click "Save Configuration" to store settings
6. **Copy test URL** - Use the generated URL in your emails

### Dynamic Image Generation

Use the saved configuration in your emails:
```
https://yourdomain.com/img/index.php?id={image_id}&t={text}
```

#### Examples

Basic usage:
```
https://yourdomain.com/img/index.php?id=1&t=Hello+World
```

Email personalization:
```
https://yourdomain.com/img/index.php?id=1&t=To:+{{name}}
```

## Project Structure

```
img/
├── admin.php              # Admin interface with drag-drop editor (password protected)
├── index.php               # Main image generation script
├── upload.php              # Image upload handler (password protected)
├── save-config.php         # Configuration save/load API (password protected)
├── auth.php                # Authentication middleware
├── login.php               # Login page
├── logout.php              # Logout handler
├── .env                    # Your credentials (create from .env.example)
├── .env.example            # Sample credentials file
├── setup-fonts.php         # Auto-download TTF fonts
├── config.php              # Configuration file (optional)
├── create-default-image.php # Script to create placeholder image
├── images/                 # Your uploaded images
│   ├── 1.jpg
│   ├── 2.jpg
│   └── default.jpg
├── fonts/                  # TrueType font files
│   ├── OpenSans.ttf
│   ├── Roboto.ttf
│   ├── Caveat.ttf
│   └── GloriaHallelujah.ttf
├── configs.json            # Saved configurations (auto-created)
├── README.md               # This file
├── DEPLOY.md               # Deployment guide
├── QUICKSTART.md           # Quick start guide
└── .htaccess               # Apache configuration
```

## Admin Interface Features

### Upload Images
- Click the upload area to select an image
- Supports JPG, PNG, GIF formats
- Automatically assigns the next available image ID
- Optimizes large images (max 1920px width)

### Text Settings
- **Preview Text** - Test with sample text
- **Font Family** - Arial, Helvetica, Georgia, Times New Roman, Courier New, Verdana, Impact
- **Font Size** - Adjustable from 12px to 120px
- **Font Weight** - Thin, Light, Normal, Medium, Bold, Black
- **Text Color** - Color picker for text
- **Shadow Color** - Color picker for text shadow

### Drag & Drop Positioning
- Click and drag text anywhere on the image
- See real-time position (pixels and percentage)
- Position is saved relative to image size

### Configuration Management
- Save settings per image ID
- Click saved configurations to load them
- Configurations persist across sessions
- Server-side storage with client-side fallback

## Setup

### 1. Upload to Server

Upload the entire `img/` folder to your web server:
```
/var/www/html/img/
```

### 2. Configure Admin Access

**Create .env file:**
```bash
# Copy the example file
cp .env.example .env

# Edit with your credentials
# ADMIN_USERNAME=admin
# ADMIN_PASSWORD=your_secure_password_here
```

**Via cPanel File Manager:**
1. Go to `public_html/img/`
2. Copy `.env.example` → Paste as `.env`
3. Edit `.env` and set your username/password

**Important:** Never commit `.env` to git! It contains your actual credentials.

### 3. Set Permissions

```bash
cd /var/www/html/img
chmod 755 .
chmod 644 *.php *.html
chmod 755 images fonts
chmod 644 images/* fonts/* 2>/dev/null || true
```

### 3. Setup Fonts (Required for font size to work)

**Quick Method - Copy from your computer:**
- **Windows:** Copy `C:\Windows\Fonts\arial.ttf` → Upload to `fonts/`
- **Mac:** Copy `/Library/Fonts/Arial.ttf` → Upload to `fonts/`

**Or Download Google Fonts as TTF:**
Open `google-fonts-guide.html` in your browser to download:
- Open Sans, Roboto, Noto Sans, Lato (all free, from Google Fonts GitHub)

**Via cPanel File Manager:**
1. Go to `public_html/img/fonts/`
2. Upload your `.ttf` file (rename to `arial.ttf` for best compatibility)

**Why fonts are needed:** Without TTF fonts, the font size slider won't work (text stays ~16px max).

### 4. Test Admin Interface

**Note:** The admin panel is now password protected.

Open in your browser:
```
https://yourdomain.com/img/admin.php
```

You will be redirected to the login page. Enter your credentials from `.env`:
- Default username: `admin`
- Default password: See `.env` file (change it immediately!)

**Security:** Make sure to:
1. Create `.env` from `.env.example`
2. Set a strong password
3. Never share or commit `.env`

### 5. Test Image Generation

```
https://yourdomain.com/img/index.php?id=1&t=Test
```

## Email Integration

### Example Email Template

```html
Hi {{name}},

Check out your personalized image below!

<img src="https://yourdomain.com/img/index.php?id=1&t=To:+{{name}}"
     alt="Personalized greeting for {{name}}"
     width="600"
     style="max-width: 100%; height: auto;">

Best regards,
Your Team
```

Replace `{{name}}` with your email provider's merge tag.

## Configuration Options

### Default Settings (in index.php)

```php
$imagesDir = __DIR__ . '/images/';
$configFile = __DIR__ . '/configs.json';
$fontsDir = __DIR__ . '/fonts/';

$defaultConfig = [
    'fontFamily' => 'Arial',
    'fontSize' => 40,
    'fontWeight' => 'normal',
    'fontColor' => '#FFFFFF',
    'shadowColor' => '#000000',
    'posXRel' => 50, // Center horizontally
    'posYRel' => 50, // Center vertically
];
```

### Cache Control

Images are cached for 1 hour by default. To change this, edit the cache headers in `index.php`:

```php
header('Cache-Control: public, max-age=3600'); // 3600 seconds = 1 hour
```

## Server Requirements

- PHP 7.0 or higher
- GD Library (usually pre-installed)
- Apache or Nginx web server
- File write permissions for `images/` and `configs.json`

## Troubleshooting

### Image not loading?
- Check PHP error log: `/var/log/apache2/error.log`
- Verify GD library: `php -m | grep gd`
- Check file permissions on `images/` folder

### Admin interface not saving configs?
- Check write permissions: `ls -la configs.json`
- Verify `save-config.php` exists
- Check browser console for errors

### Upload not working?
- Ensure `upload.php` exists
- Check PHP upload limits in `php.ini`:
  ```ini
  upload_max_filesize = 10M
  post_max_size = 10M
  ```
- Verify write permissions on `images/` folder

### Text not visible?
- Adjust `fontColor` in admin interface
- Try darker colors for light backgrounds
- Increase font size

## API Endpoints

### Save Configuration
```http
POST /save-config.php
Content-Type: application/json

{
  "imageId": "1",
  "fontFamily": "Arial",
  "fontSize": "40",
  "fontColor": "#FFFFFF",
  "shadowColor": "#000000",
  "posXRel": "50",
  "posYRel": "50"
}
```

### Load Configuration
```http
GET /save-config.php?id=1
```

### List All Configurations
```http
GET /save-config.php
```

### Upload Image
```http
POST /upload.php
Content-Type: multipart/form-data

image: <file>
```

## Security

- Image IDs are forced to integers to prevent directory traversal
- File types are validated (JPG, PNG, GIF only)
- Uploaded images are automatically optimized
- No direct database access required
- Configurations stored in JSON files

## Performance Tips

1. **Optimize images** before uploading (TinyPNG, ImageOptim)
2. **Use JPEG** for photos (smaller file size)
3. **Enable caching** in `.htaccess` (already included)
4. **Consider CDN** for high-traffic sites
5. **Use TrueType fonts** for better rendering quality

## Support

For detailed deployment instructions, see [DEPLOY.md](DEPLOY.md)

For quick setup, see [QUICKSTART.md](QUICKSTART.md)

## Changelog

### v2.0 (Current)
- Added web-based admin interface
- Drag & drop text positioning
- Font customization options
- Configuration management system
- Image upload with auto-ID assignment
- TrueType font support
- Server-side configuration storage

### v1.0
- Basic dynamic image generation
- URL parameter support
- Built-in GD fonts

---

Built for personalized email campaigns
