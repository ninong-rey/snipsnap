#!/bin/bash

echo "=== Fixing video references properly ==="

# Fix notifications.blade.php - show what we're fixing
echo "1. Fixing notifications.blade.php..."
echo "   Before:"
grep -n "asset('storage/' ..*video" resources/views/notifications.blade.php

# Use a different approach - create temporary files
cp resources/views/notifications.blade.php resources/views/notifications.blade.php.bak

# Fix thumbnail_url
perl -i -pe "s/asset\('storage\/' \. \\\\\$notification->video->thumbnail_url\)/\\\\\$notification->video->thumbnail_url/g" resources/views/notifications.blade.php

# Fix url
perl -i -pe "s/asset\('storage\/' \. \\\\\$notification->video->url\)/\\\\\$notification->video->url/g" resources/views/notifications.blade.php

# Fix JavaScript videoThumbnail
perl -i -pe "s|'/storage/' \+ videoThumbnail|videoThumbnail|g" resources/views/notifications.blade.php

echo "   After:"
grep -n "asset('storage/' ..*video" resources/views/notifications.blade.php || echo "   ✅ No more video storage references!"

# Fix video.blade.php
echo "2. Fixing video.blade.php..."
echo "   Before:"
grep -n "asset('storage/' ..*creatorVideo" resources/views/video.blade.php

cp resources/views/video.blade.php resources/views/video.blade.php.bak
perl -i -pe "s/asset\('storage\/' \. \\\\\$creatorVideo->url\)/\\\\\$creatorVideo->url/g" resources/views/video.blade.php

echo "   After:"
grep -n "asset('storage/' ..*creatorVideo" resources/views/video.blade.php || echo "   ✅ No more creatorVideo storage references!"

echo ""
echo "=== Checking remaining storage references ==="
echo "Video URL/thumbnail references (should be empty):"
grep -r "storage/" resources/views/ | grep -E "(video.*url|thumbnail_url|source.*src)" | grep -v "avatar"

echo ""
echo "All storage references (avatars are OK):"
grep -r "storage/" resources/views/ | head -10

