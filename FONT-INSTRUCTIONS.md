# 🔤 Font Setup Instructions

Since auto-download didn't work, here's the manual setup:

## Quick Solution (5 minutes)

### Option 1: Copy from Your Computer (Easiest)

**Windows:**
1. Press `Win + R`, type: `fonts`
2. Find `Arial` or `Segoe UI`
3. Right-click → Copy
4. Go to cPanel File Manager → `img/fonts/`
5. Paste the file
6. Rename to `arial.ttf`

**Mac:**
1. Open Finder → Go → `/Library/Fonts/`
2. Find `Arial.ttf` or `Helvetica.ttf`
3. Copy it
4. Upload via cPanel to `img/fonts/`

### Option 2: Download Free Font

**Site 1:** https://www.fontsquirrel.com/fonts/arial
1. Click "Download OTF" (works as TTF)
2. Upload to `fonts/` folder as `arial.ttf`

**Site 2:** https://www.1001freefonts.com/arial-fonts.html
1. Download any Arial variant
2. Upload to `fonts/` folder

---

## Step-by-Step with cPanel

1. **Download a TTF font** to your computer (use any of the sources above)

2. **Open cPanel** → File Manager

3. **Navigate to** `public_html/img/fonts/`

4. **Click Upload** button

5. **Select the font file** from your computer

6. **Verify it uploaded:**
   - You should see `arial.ttf` (or `opensans.ttf`, etc.) in the fonts folder

7. **Test:** Open `https://yourdomain.com/img/admin.html`
   - Change font size slider
   - Save configuration
   - Test the image URL

---

## Font File Names

The code looks for these font files:
- `arial.ttf` (recommended - works with most "Arial" selections)
- `opensans.ttf` (if you select Open Sans)
- `roboto.ttf` (if you select Roboto)

**Tip:** Any `.ttf` file renamed to `arial.ttf` will work as the default fallback.

---

## Verify It's Working

After uploading the font, check:
```
https://yourdomain.com/img/index.php?id=1&t=Hello+World
```

If the text is still tiny, clear your browser cache (the image is cached for 1 hour).
