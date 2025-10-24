<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SnipSnap Studio</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
/* ==== BASE ==== */
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', 'Segoe UI', sans-serif; }
body { background-color: #f9f9f9; color: #161823; display: flex; height: 100vh; overflow: hidden; }
.container { display: flex; width: 100%; }

/* ==== SIDEBAR ==== */
.sidebar { width: 240px; background: #fff; border-right: 1px solid #eee; display: flex; flex-direction: column; padding: 24px 0; }
.logo { display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 22px; color: #161823; padding: 0 20px 24px; }
.logo img { width: 32px; height: 32px; }
.upload-btn { margin: 0 20px 20px; padding: 12px 16px; background: #fe2c55; border: none; border-radius: 8px; color: #fff; font-weight: 600; font-size: 15px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.2s ease; }
.upload-btn:hover { background: #e0264c; transform: translateY(-1px); }
.menu-item { padding: 12px 20px; display: flex; align-items: center; gap: 12px; color: #161823; text-decoration: none; transition: 0.2s; }
.menu-item i { width: 22px; text-align: center; }
.menu-item:hover, .menu-item.active { background: #f7f7f7; color: #fe2c55; font-weight: 600; }

/* ==== MAIN ==== */
.main-content { flex: 1; padding: 40px; overflow-y: auto; }
.header h1 { font-size: 28px; font-weight: 700; margin-bottom: 8px; }
.header p { color: #8a8b8f; font-size: 16px; }

.upload-area { background: #fff; border: 2px dashed #e5e5e5; border-radius: 10px; text-align: center; padding: 80px 40px; margin-top: 30px; transition: 0.3s border-color; }
.upload-area.drag-over { border-color: #fe2c55; background: #fff5f7; }

.upload-icon { font-size: 56px; color: #fe2c55; margin-bottom: 18px; }
.upload-text { font-size: 20px; font-weight: 600; margin-bottom: 6px; }
.upload-subtext { color: #8a8b8f; font-size: 15px; margin-bottom: 20px; }

.select-video-btn { background: #fe2c55; color: #fff; border: none; padding: 12px 28px; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
.select-video-btn:hover { background: #e0264c; }
.file-input { display: none; }

.video-preview { display: none; max-width: 380px; margin: 30px auto 0; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
.video-preview video { width: 100%; display: block; }

.caption-section { background: #fff; border-radius: 8px; margin-top: 40px; padding: 24px; }
.caption-section h3 { font-size: 18px; margin-bottom: 12px; font-weight: 600; }
.caption-input { width: 100%; border: 1px solid #ddd; border-radius: 6px; padding: 14px; font-size: 16px; resize: vertical; min-height: 80px; }
.caption-input:focus { outline: none; border-color: #fe2c55; }

/* ==== ACTIONS ==== */
.upload-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 28px; border-top: 1px solid #eee; padding-top: 20px; }
.cancel-btn { background: #f8f8f8; color: #161823; border: 1px solid #ddd; border-radius: 6px; padding: 12px 24px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
.cancel-btn:hover { background: #eee; }
.upload-submit-btn { background: #fe2c55; color: #fff; border: none; border-radius: 6px; padding: 12px 24px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
.upload-submit-btn:hover { background: #e0264c; }
.upload-submit-btn:disabled { background: #ccc; cursor: not-allowed; }

.upload-success { background: #e6ffe6; color: #0f5132; border: 1px solid #b6f2b6; padding: 14px; border-radius: 6px; text-align: center; margin-top: 20px; display: none; }
.upload-success.show { display: block; }

/* ==== PROGRESS BAR ==== */
#uploadProgressBar {
    width: 0%;
    height: 100%;
    background: #fe2c55;
    text-align: center;
    color: #fff;
    line-height: 20px;
    transition: width 0.4s ease; /* smooth animation */
}

#progressContainer { display:none; margin-top: 10px; }

@media (max-width: 768px) {
  .container { flex-direction: column; }
  .sidebar { width: 100%; flex-direction: row; justify-content: space-around; border-right: none; border-bottom: 1px solid #eee; }
  .main-content { padding: 20px; }
}
</style>
</head>
<body>
<div class="container">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">
      <img src="{{ secure_asset('default-avatar.png') }}" alt="Avatar">
      SnipSnap Studio
    </div>
    <a href="{{ route('upload') }}" class="upload-btn active">
      <i class="fas fa-plus"></i> Upload
    </a>
    <a href="{{ route('my-web') }}" class="menu-item"><i class="fas fa-home"></i> Home</a>
  </div>

  <!-- Main -->
  <div class="main-content">
    <div class="header">
      <h1>Upload Your Video</h1>
      <p>Share your moments with the SnipSnap community</p>
    </div>

    <div id="successBox" class="upload-success">
      <i class="fas fa-check-circle"></i> Video uploaded successfully!
    </div>

    <!-- Upload Form -->
    <form id="uploadForm" method="POST" enctype="multipart/form-data" action="{{ secure_url(route('upload.store')) }}">  
      @csrf

      <!-- Progress Bar -->
      <div id="uploadProgressContainer" style="display:none; margin-top: 20px;">
        <div style="background: #eee; border-radius: 6px; overflow: hidden; height: 20px;">
          <div id="uploadProgressBar">0%</div>
        </div>
      </div>

      <!-- Upload Area -->
      <div class="upload-area" id="uploadArea">
        <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
        <div class="upload-text">Select video to upload</div>
        <div class="upload-subtext">or drag and drop your video file</div>
        <button type="button" class="select-video-btn" id="selectVideoBtn">Select Video</button>
        <input type="file" id="videoFile" name="video" class="file-input" accept="video/*">
      </div>

      <!-- Preview -->
      <div class="video-preview" id="videoPreview">
        <video controls></video>
      </div>

      <!-- Caption -->
      <div class="caption-section">
        <h3>Caption</h3>
        <textarea id="captionInput" name="caption" class="caption-input" placeholder="Write a caption..."></textarea>
      </div>

      <!-- Actions -->
      <div class="upload-actions">
        <button type="button" class="cancel-btn" onclick="window.location.href='{{ route('my-web') }}'">Cancel</button>
        <button type="submit" id="uploadSubmitBtn" class="upload-submit-btn" disabled>
          <i class="fas fa-upload"></i> Upload Video
        </button>
      </div>
    </form>
  </div>
</div>

<script>
const fileInput = document.getElementById('videoFile');
const selectBtn = document.getElementById('selectVideoBtn');
const previewBox = document.getElementById('videoPreview');
const previewVideo = previewBox.querySelector('video');
const uploadBtn = document.getElementById('uploadSubmitBtn');
const successBox = document.getElementById('successBox');
const uploadForm = document.getElementById('uploadForm');
const uploadArea = document.getElementById('uploadArea');
const progressBar = document.getElementById('uploadProgressBar');

// --- Open file dialog ---
selectBtn.addEventListener('click', () => fileInput.click());

// --- Preview video ---
fileInput.addEventListener('change', () => {
    const file = fileInput.files[0];
    if (file) {
        previewVideo.src = URL.createObjectURL(file);
        previewBox.style.display = 'block';
        uploadBtn.disabled = false;
    }
});

// --- Drag & drop ---
uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('drag-over'); });
uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('drag-over'));
uploadArea.addEventListener('drop', e => {
    e.preventDefault();
    uploadArea.classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (file) {
        fileInput.files = e.dataTransfer.files;
        previewVideo.src = URL.createObjectURL(file);
        previewBox.style.display = 'block';
        uploadBtn.disabled = false;
    }
});

// --- Submit form via AJAX ---
uploadForm.addEventListener('submit', e => {
    e.preventDefault();
    const file = fileInput.files[0];
    if (!file) return alert('Please select a video.');

    const formData = new FormData(uploadForm);

    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
    successBox.classList.remove('show');

    // Show progress container
    const progressContainer = document.getElementById('uploadProgressContainer');
    progressContainer.style.display = 'block';
    progressBar.style.width = '0%';
    progressBar.textContent = '0%';

    const xhr = new XMLHttpRequest();
    xhr.open('POST', uploadForm.action.replace('http://', 'https://'), true);
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    xhr.upload.addEventListener('progress', e => {
        if (e.lengthComputable) {
            const percent = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = percent + '%';
            progressBar.textContent = percent + '%';
        }
    });

    xhr.onload = function() {
        if (xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);
            if (data.success) {
                progressBar.style.width = '100%';
                progressBar.textContent = '100%';
                successBox.classList.add('show');
                uploadBtn.innerHTML = '<i class="fas fa-check"></i> Uploaded!';
                setTimeout(() => {
                    window.location.href = data.redirect_url || "{{ route('my-web') }}";
                }, 1500);
            } else {
                alert('Upload failed: ' + (data.message || 'Unknown error'));
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Upload Video';
            }
        } else {
            alert('Upload failed: Server error');
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Upload Video';
        }
    };

    xhr.send(formData);
});
</script>
</body>
</html>

