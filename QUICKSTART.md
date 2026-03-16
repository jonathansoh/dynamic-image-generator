# Quick Start Guide

Get your dynamic image generator running in 5 minutes with the new admin interface!

## 1. Upload to Server

Upload the entire `dynamic-image/` folder to your web server:
```
/var/www/html/dynamic-image/
```

## 2. Set Permissions

```bash
cd /var/www/html/dynamic-image
chmod 755 .
chmod 644 *.php *.html
chmod 755 images fonts
```

## 3. Open Admin Interface

Open your browser and go to:
```
https://yourdomain.com/dynamic-image/admin.html
```

## 4. Configure Your First Image

### Step 1: Upload an Image
- Click the "📁 Click to upload image" box
- Select your image file (JPG, PNG, or GIF)
- The system will automatically assign it an ID (e.g., 1, 2, 3...)

### Step 2: Customize Text Settings
- **Preview Text**: Enter sample text like "To: Simon"
- **Font Family**: Choose from Arial, Georgia, Impact, etc.
- **Font Size**: Drag the slider (12px to 120px)
- **Font Weight**: Select weight (Normal, Bold, etc.)
- **Text Color**: Pick a color that contrasts with your image
- **Shadow Color**: Set shadow color for better visibility

### Step 3: Position the Text
- Click and drag the text on the preview image
- Place it exactly where you want it
- Position is shown in pixels and percentage

### Step 4: Save Configuration
- Set the Image ID (usually auto-filled after upload)
- Click "Save Configuration"
- Configuration is saved to the server

### Step 5: Test the URL
- Copy the URL from "🔗 Test URL"
- Or click "Open in New Tab" to see the result

## 5. Use in Emails

Copy this HTML into your email template:
```html
<img src="https://yourdomain.com/dynamic-image/index.php?id=1&t=To:+{{name}}"
     alt="Hello {{name}}"
     width="600">
```

Replace `{{name}}` with your email provider's merge tag (e.g., `{NAME}`, `%NAME%`).

## URL Parameters

- `id` - Image number (1, 2, 3, etc.)
- `t` - Text to show (URL encode spaces: use `+` or `%20`)

## Examples

### Email Personalization
```
?id=1&t=To:+{{name}}
```

### Custom Messages
```
?id=1&t=Welcome+to+our+newsletter
```

### Multiple Words
```
?id=1&t=Hello+John+Smith
```

## That's It! 🎉

You now have a fully functional dynamic image generator with:
- ✅ Drag-and-drop text positioning
- ✅ Font customization
- ✅ Configuration management
- ✅ Server-side storage
- ✅ Real-time preview

For detailed information, see:
- [README.md](README.md) - Full documentation
- [DEPLOY.md](DEPLOY.md) - Deployment guide

## Troubleshooting

**Admin interface won't load?**
- Check file permissions
- Verify admin.html exists
- Check browser console for errors

**Can't upload images?**
- Check write permissions on `images/` folder
- Verify `upload.php` exists
- Check PHP upload limits

**Configs not saving?**
- Check write permissions on directory (needs to create `configs.json`)
- Verify `save-config.php` exists
- Check browser console for errors

**Need help?** See the full [README.md](README.md) for detailed troubleshooting.
