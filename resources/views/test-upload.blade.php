<!DOCTYPE html>
<html>
<head>
    <title>Test Upload</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body style="padding: 20px; font-family: Arial, sans-serif;">
    <h2>Test Video Upload to Cloudinary</h2>
    <form id="uploadForm" enctype="multipart/form-data">
        <input type="file" name="video" accept="video/*" required style="margin: 10px 0;">
        <br>
        <button type="submit" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Upload Test Video
        </button>
    </form>
    <div id="result" style="margin-top: 20px; padding: 15px; border: 1px solid #ccc; border-radius: 5px; background: #f9f9f9;"></div>

    <script>
    document.getElementById('uploadForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('video', e.target.video.files[0]);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        const resultDiv = document.getElementById('result');
        resultDiv.innerHTML = 'Uploading...';
        
        try {
            const response = await fetch('/test-upload', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            resultDiv.innerHTML = '<h3>Upload Result:</h3><pre>' + JSON.stringify(result, null, 2) + '</pre>';
            console.log('Upload result:', result);
            
        } catch (error) {
            console.error('Upload failed:', error);
            resultDiv.innerHTML = 'Error: ' + error.message;
        }
    });
    </script>
</body>
</html>