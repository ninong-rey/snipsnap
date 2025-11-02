#!/bin/bash

# Array of files to update
files=(
  "resources/views/web.blade.php"
  "resources/views/profile.blade.php" 
  "resources/views/video.blade.php"
  "resources/views/following.blade.php"
  "resources/views/notifications.blade.php"
)

# Fix each file
for file in "${files[@]}"; do
  if [ -f "$file" ]; then
    echo "Fixing $file"
    
    # Replace video URL patterns
    sed -i '' "s/asset('storage\/' . \\\$video->url)/\$video->url/g" "$file"
    sed -i '' "s/secure_asset('storage\/' . \\\$video->url)/\$video->url/g" "$file"
    sed -i '' "s/asset('storage\/' . \\\$video->file_path)/\$video->url/g" "$file"
    sed -i '' "s/asset('storage\/' . \\\$videoUrl)/\$videoUrl/g" "$file"
    sed -i '' "s/\\'storage\\/' . \\\$video->url/\$video->url/g" "$file"
    
    # Replace JavaScript patterns
    sed -i '' "s|'/storage/' + videoData.url|videoData.url|g" "$file"
    sed -i '' "s|'/storage/' + videoThumbnail|videoThumbnail|g" "$file"
    sed -i '' "s|'/storage/' + notification.video.url|notification.video.url|g" "$file"
    
    echo "✅ Fixed $file"
  else
    echo "❌ File not found: $file"
  fi
done

echo "🎯 Fix complete!"
