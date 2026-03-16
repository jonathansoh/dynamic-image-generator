# Deployment Guide

## Quick Setup

1. **Upload files** to your server:
   ```bash
   # Using SCP (Linux/Mac)
   scp -r img/ user@yourserver.com:/var/www/html/
   
   # Or upload via FTP/SFTP
   ```

2. **Add your images** to the `images/` folder:
   - Create images named: `1.jpg`, `2.jpg`, `3.jpg`, etc.
   - Recommended size: 800x400px or similar ratio
   - File types: JPG, PNG, GIF supported

3. **Test your installation**:
   ```
   https://yourdomain.com/img/index.php?id=1&t=Test
   ```

## Server Requirements

- ✅ PHP 7.0 or higher
- ✅ GD Library (usually pre-installed)
- ✅ Apache or Nginx web server

## Check GD Library

Run this on your server:
```bash
php -m | grep gd
```

If not installed:
```bash
# Ubuntu/Debian
sudo apt-get install php-gd

# CentOS/RHEL
sudo yum install php-gd

# macOS (Homebrew)
brew install php
```

## Upload Images

### Option 1: Upload via SFTP
```bash
sftp user@yourserver.com
cd /var/www/html/img/images
put your-image.jpg 1.jpg
put another-image.png 2.png
```

### Option 2: Create a default image
Run this on your server:
```bash
cd /var/www/html/img
php create-default-image.php
```

## Configure Your Server

### Apache (most common)
```bash
# Enable mod_rewrite (if not already enabled)
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Nginx
Add to your server config:
```nginx
location /img/ {
    try_files $uri $uri/ /img/index.php?$query_string;
}
```

## Set Permissions

```bash
# Set proper permissions
cd /var/www/html/img
chmod 755 .
chmod 644 index.php config.php
chmod 755 images
chmod 644 images/*
```

## Test URLs

After deployment, test these URLs:

1. **Basic test:**
   ```
   https://yourdomain.com/img/index.php?id=1&t=Hello
   ```

2. **With special characters:**
   ```
   https://yourdomain.com/img/index.php?id=1&t=Hello+World%21
   ```

3. **Email personalization example:**
   ```
   https://yourdomain.com/img/index.php?id=1&t=To:+John+Smith
   ```

## URL Shortening (Optional)

If you want shorter URLs, add to `.htaccess`:

```apache
RewriteEngine On
RewriteRule ^di/([^/]+)/([^/]+)$ index.php?id=$1&t=$2 [L]
```

Then use:
```
https://yourdomain.com/di/1/Hello+World
```

## Common Issues

### Image not showing
- Check PHP error log: `/var/log/apache2/error.log`
- Verify GD library: `php -m | grep gd`
- Check file permissions on `images/` folder

### Text not visible
- Edit `config.php` and change `font_color`
- Try darker colors for light backgrounds
- Adjust `font_size` in config

### Images folder not found
- Ensure `images/` folder exists in the same directory as `index.php`
- Check absolute path in `config.php`

## Performance Tips

1. **Use JPEG** for photos (smaller file size)
2. **Use PNG** for graphics with transparency
3. **Optimize images** before uploading (TinyPNG, ImageOptim)
4. **Enable caching** in `.htaccess` (already included)
5. **Consider CDN** for high-traffic sites

## Email Integration

In your email template, use:

```html
<img src="https://text.jonathansoh.com/img/index.php?id=1&t=To:+{{name}}" 
     alt="Personalized greeting for {{name}}" 
     width="600"
     style="max-width: 100%; height: auto;">
```

Replace `{{name}}` with your email provider's merge tag (e.g., `{NAME}`, `%NAME%`, etc.).

## Monitoring

Track image usage by checking server logs:
```bash
tail -f /var/log/apache2/access.log | grep img
```

## Backup

Regular backup of `images/` folder:
```bash
tar -czf imgs-backup-$(date +%Y%m%d).tar.gz images/
```

---

Need help? Check the README.md or contact your developer.
