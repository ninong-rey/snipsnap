<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SnipSnap Studio</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
/* ==== SKELETON LOADER ==== */
.skeleton {
  background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
  background-size: 400% 100%;
  animation: shimmer 2s infinite ease-in-out;
  border-radius: 4px;
}

@keyframes shimmer {
  0% { background-position: -200% 0; }
  100% { background-position: 200% 0; }
}

.sidebar.skeleton-loading .logo::before,
.sidebar.skeleton-loading .upload-btn::before,
.sidebar.skeleton-loading .menu-item::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
  background-size: 400% 100%;
  animation: shimmer 2s infinite ease-in-out;
  border-radius: 8px;
  z-index: 1;
}

.sidebar.skeleton-loading .logo > *,
.sidebar.skeleton-loading .upload-btn > *,
.sidebar.skeleton-loading .menu-item > * {
  opacity: 0;
}

.main-content.skeleton-loading .header h1::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 300px;
  height: 32px;
  background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
  background-size: 400% 100%;
  animation: shimmer 2s infinite ease-in-out;
  border-radius: 4px;
}

/* ==== BASE STYLES ==== */
* { margin:0; padding:0; box-sizing:border-box; font-family:'Inter','Segoe UI',sans-serif; }
body { background:#f9f9f9; color:#161823; display:flex; height:100vh; overflow:hidden; }
.container { display:flex; width:100%; }

.sidebar { width:240px; background:#fff; border-right:1px solid #eee; display:flex; flex-direction:column; padding:24px 0; }
.logo { display:flex; align-items:center; gap:10px; font-weight:700; font-size:22px; color:#161823; padding:0 20px 24px; }
.logo img { width:32px; height:32px; }
.upload-btn { margin:0 20px 20px; padding:12px 16px; background:#fe2c55; border:none; border-radius:8px; color:#fff; font-weight:600; font-size:15px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:0.2s ease; }
.upload-btn:hover { background:#e0264c; }
.menu-item { padding:12px 20px; display:flex; align-items:center; gap:12px; color:#161823; text-decoration:none; transition:0.2s; }
.menu-item i { width:22px; text-align:center; }
.menu-item:hover, .menu-item.active { background:#f7f7f7; color:#fe2c55; font-weight:600; }

.main-content { flex:1; padding:40px; overflow-y:auto; transition: opacity 0.3s ease; }
.main-content.skeleton-loading { opacity: 0.9; }
.header h1 { font-size:28px; font-weight:700; margin-bottom:8px; }
.header p { color:#8a8b8f; font-size:16px; }

.upload-area { background:#fff; border:2px dashed #e5e5e5; border-radius:10px; text-align:center; padding:80px 40px; margin-top:30px; transition:0.3s border-color; position: relative; }
.upload-area.drag-over { border-color:#fe2c55; background:#fff5f7; }

.upload-icon { font-size:56px; color:#fe2c55; margin-bottom:18px; }
.upload-text { font-size:20px; font-weight:600; margin-bottom:6px; }
.upload-subtext { color:#8a8b8f; font-size:15px; margin-bottom:20px; }
.upload-info { color:#666; font-size:12px; margin-top:10px; line-height:1.4; }

.select-video-btn { background:#fe2c55; color:#fff; border:none; padding:12px 28px; border-radius:8px; font-size:16px; font-weight:600; cursor:pointer; transition:background 0.2s; }
.select-video-btn:hover { background:#e0264c; }
.file-input { display:none; }

.video-preview { display:none; max-width:380px; margin:30px auto 0; border-radius:8px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
.video-preview video { width:100%; display:block; }

.caption-section { background:#fff; border-radius:8px; margin-top:40px; padding:24px; position: relative; }
.caption-section h3 { font-size:18px; margin-bottom:12px; font-weight:600; }
.caption-input { width:100%; border:1px solid #ddd; border-radius:6px; padding:14px; font-size:16px; resize:vertical; min-height:80px; }
.caption-input:focus { outline:none; border-color:#fe2c55; }

.upload-actions { display:flex; justify-content:flex-end; gap:12px; margin-top:28px; border-top:1px solid #eee; padding-top:20px; position: relative; }
.cancel-btn { background:#f8f8f8; color:#161823; border:1px solid #ddd; border-radius:6px; padding:12px 24px; font-weight:600; cursor:pointer; transition:background 0.2s; }
.cancel-btn:hover { background:#eee; }
.upload-submit-btn { background:#fe2c55; color:#fff; border:none; border-radius:6px; padding:12px 24px; font-weight:600; cursor:pointer; transition:background 0.2s; }
.upload-submit-btn:hover { background:#e0264c; }
.upload-submit-btn:disabled { background:#ccc; cursor:not-allowed; }

.upload-success { background:#e6ffe6; color:#0f5132; border:1px solid #b6f2b6; padding:14px; border-radius:6px; text-align:center; margin-top:20px; display:none; }
.upload-success.show { display:block; }

.upload-error { background:#ffe6e6; color:#721c24; border:1px solid #f5c6cb; padding:14px; border-radius:6px; text-align:center; margin-top:20px; display:none; }
.upload-error.show { display:block; }

.debug-info { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 15px; margin-top: 20px; font-family: monospace; font-size: 12px; display: none; }
.debug-info.show { display: block; }

#uploadProgressContainer { display:none; margin-top:20px; height:20px; border-radius:8px; overflow:hidden; background:#eee; position:relative; }
#uploadProgressBar { width:0%; height:100%; background:linear-gradient(90deg,#ff0050,#ffb400,#00ffea,#ff0050); background-size:300% 100%; border-radius:8px; transition:width 0.2s ease-out; animation:gradientShift 2s linear infinite; }
#uploadProgressBarText { position:absolute; top:0; left:50%; transform:translateX(-50%); height:100%; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:bold; color:#fff; text-shadow:0 0 3px rgba(0,0,0,0.5); }
@keyframes gradientShift { 0% { background-position:0% 0%; } 100% { background-position:100% 0%; } }

.file-info { background:#f8f9fa; border:1px solid #e9ecef; border-radius:6px; padding:12px; margin-top:15px; display:none; }
.file-info.show { display:block; }
.file-info div { margin:5px 0; font-size:14px; }

@media(max-width:768px){.container{flex-direction:column;}.sidebar{width:100%;flex-direction:row;justify-content:space-around;border-right:none;border-bottom:1px solid #eee;}.main-content{padding:20px;}}
</style>
</head>
<body>
<div class="container">
  <div class="sidebar" id="sidebar">
   <div class="logo">
  <img src="{{ secure_asset('image/snipsnap.png') }}" alt="SnipSnap" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
  <div style="display:none; width:32px;height:32px;background:#fe2c55;border-radius:6px;align-items:center;justify-content:center;color:white;font-weight:bold;font-size:14px;">
    SS
  </div>
  SnipSnap Studio
</div>
    <a href="{{ route('upload') }}" class="upload-btn active"><i class="fas fa-plus"></i> Upload</a>
    <a href="{{ route('my-web') }}" class="menu-item"><i class="fas fa-home"></i> Home</a>
  </div>

  <div class="main-content" id="mainContent">
  <div class="header">
    <h1>Upload Your Video</h1>
    <p>Share your moments with the SnipSnap community</p>
  </div>

  <div id="successBox" class="upload-success" style="display: none;">
    <i class="fas fa-check-circle"></i> Video uploaded successfully!
  </div>

  <div id="errorBox" class="upload-error" style="display: none;">
    <i class="fas fa-exclamation-circle"></i> <span id="errorMessage"></span>
  </div>

    <!-- Upload Form - TESTING MULTIPLE ENDPOINTS -->
    <form id="uploadForm" method="POST" enctype="multipart/form-data">
      @csrf

      <div id="uploadProgressContainer">
        <div id="uploadProgressBar"></div>
        <div id="uploadProgressBarText">0%</div>
      </div>

      <div class="upload-area" id="uploadArea">
        <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
        <div class="upload-text">Select video to upload</div>
        <div class="upload-subtext">or drag and drop your video file</div>
        <div class="upload-info">
          <small>Recommended: MP4 format, under 5MB for testing</small>
        </div>
        <button type="button" class="select-video-btn" id="selectVideoBtn">Select Video</button>
        <input type="file" id="videoFile" name="video" class="file-input" accept="video/*">
      </div>

      <div id="fileInfo" class="file-info">
        <div><strong>File:</strong> <span id="fileName"></span></div>
        <div><strong>Size:</strong> <span id="fileSize"></span></div>
        <div><strong>Type:</strong> <span id="fileType"></span></div>
      </div>

      <div class="video-preview" id="videoPreview">
        <video controls></video>
      </div>

      <div class="caption-section">
        <h3>Caption</h3>
        <textarea id="captionInput" name="caption" class="caption-input" placeholder="Write a caption..."></textarea>
      </div>

      <div class="upload-actions">
        <button type="button" class="cancel-btn" id="cancelUploadBtn" style="display:none;">
          <i class="fas fa-times"></i> Cancel Upload
        </button>
        <button type="button" class="cancel-btn" id="backBtn" onclick="window.location.href='{{ route('my-web') }}'">
          <i class="fas fa-arrow-left"></i> Back
        </button>
        
        <!-- Test different endpoints -->
        <button type="button" class="upload-submit-btn" id="testUploadBtn" data-endpoint="/upload">
          <i class="fas fa-vial"></i> Test Upload
        </button>
        
        <button type="button" class="cancel-btn" id="testRoutesBtn">
          <i class="fas fa-route"></i> Test Routes
        </button>
      </div>
    </form>
  </div>
</div>

<script>
// ===== SKELETON LOADER =====
function showSkeleton() {
  const mainContent = document.getElementById('mainContent');
  const sidebar = document.getElementById('sidebar');
  if (mainContent) mainContent.classList.add('skeleton-loading');
  if (sidebar) sidebar.classList.add('skeleton-loading');
}

function hideSkeleton() {
  const mainContent = document.getElementById('mainContent');
  const sidebar = document.getElementById('sidebar');
  if (mainContent) mainContent.classList.remove('skeleton-loading');
  if (sidebar) sidebar.classList.remove('skeleton-loading');
}

function initSkeletonLoader() {
  showSkeleton();
  setTimeout(() => hideSkeleton(), 1500);
}

// ===== UPLOAD CONFIGURATION =====
const UPLOAD_CONFIG = {
  MAX_FILE_SIZE: 10 * 1024 * 1024, // 10MB
  ALLOWED_TYPES: ['video/mp4', 'video/avi', 'video/mov', 'video/wmv'],
  TIMEOUT: 60000
};

let currentXHR = null;

// Helper functions
function showError(message) {
  const errorMessage = document.getElementById('errorMessage');
  const errorBox = document.getElementById('errorBox');
  if (errorMessage) errorMessage.textContent = message;
  if (errorBox) errorBox.classList.add('show');
  console.error('Error:', message);
}

function hideError() {
  const errorBox = document.getElementById('errorBox');
  if (errorBox) errorBox.classList.remove('show');
}

function showSuccess(message) {
  const successBox = document.getElementById('successBox');
  if (successBox) {
    successBox.textContent = message;
    successBox.classList.add('show');
  }
}

function formatFileSize(bytes) {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function validateFile(file) {
  console.log('Validating file:', file);
  if (!file) {
    showError('Please select a file');
    return false;
  }
  if (!UPLOAD_CONFIG.ALLOWED_TYPES.includes(file.type)) {
    showError('Please select a valid video file (MP4, AVI, MOV, WMV)');
    return false;
  }
  if (file.size > UPLOAD_CONFIG.MAX_FILE_SIZE) {
    showError(`File too large. Maximum size is 10MB. Your file is ${formatFileSize(file.size)}`);
    return false;
  }
  console.log('File validation passed');
  return true;
}

// Upload function
function uploadVideo() {
  console.log('uploadVideo function called');
  
  const fileInput = document.getElementById('videoFile');
  const file = fileInput.files[0];
  
  console.log('Selected file:', file);
  
  if (!file || !validateFile(file)) {
    console.log('File validation failed');
    return;
  }

  hideError(); // Clear any previous errors

  // Update UI
  const uploadBtn = document.getElementById('testUploadBtn');
  const progressContainer = document.getElementById('uploadProgressContainer');
  const progressBar = document.getElementById('uploadProgressBar');
  const progressText = document.getElementById('uploadProgressBarText');
  
  if (uploadBtn) {
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
  }
  if (progressContainer) progressContainer.style.display = 'block';
  if (progressBar) progressBar.style.width = '0%';
  if (progressText) progressText.textContent = '0%';

  const formData = new FormData();
  formData.append('video', file);
  formData.append('caption', document.getElementById('captionInput').value);
  formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

  console.log('FormData created, starting XHR...');

  currentXHR = new XMLHttpRequest();
  currentXHR.timeout = UPLOAD_CONFIG.TIMEOUT;
  currentXHR.open('POST', '/upload', true);
  currentXHR.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

  // Progress tracking
  currentXHR.upload.addEventListener('progress', (e) => {
    console.log('Upload progress:', e);
    if (e.lengthComputable) {
      const percent = Math.round((e.loaded / e.total) * 100);
      if (progressBar) progressBar.style.width = percent + '%';
      if (progressText) progressText.textContent = percent + '%';
    }
  });

  currentXHR.onload = function() {
    console.log('XHR onload - Status:', currentXHR.status);
    console.log('Response:', currentXHR.responseText);
    
    // Reset upload button
    if (uploadBtn) {
      uploadBtn.disabled = false;
      uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Upload Video';
    }
    
    if (currentXHR.status === 200) {
      try {
        const response = JSON.parse(currentXHR.responseText);
        console.log('Parsed response:', response);
        
        if (response.success) {
          showSuccess('✅ Video uploaded successfully! Redirecting...');
          setTimeout(() => {
            window.location.href = response.redirect_url || '/web';
          }, 1500);
        } else {
          showError('Upload failed: ' + (response.message || 'Unknown error'));
        }
      } catch (e) {
        console.error('JSON parse error:', e);
        showError('Error processing server response');
      }
    } else {
      showError('Upload failed. Please try again.');
    }
  };

  currentXHR.onerror = function() {
    console.error('XHR onerror');
    showError('Network error. Please check your connection.');
    // Reset upload button
    if (uploadBtn) {
      uploadBtn.disabled = false;
      uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Upload Video';
    }
  };

  currentXHR.ontimeout = function() {
    console.error('XHR ontimeout');
    showError('Upload timed out. Please try again.');
    // Reset upload button
    if (uploadBtn) {
      uploadBtn.disabled = false;
      uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Upload Video';
    }
  };

  console.log('Sending XHR request...');
  currentXHR.send(formData);
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
  console.log('DOM loaded - setting up event listeners');
  
  const fileInput = document.getElementById('videoFile');
  const uploadBtn = document.getElementById('testUploadBtn');
  const selectBtn = document.getElementById('selectVideoBtn');
  
  console.log('File input:', fileInput);
  console.log('Upload button:', uploadBtn);
  console.log('Select button:', selectBtn);

  // Upload button click
  if (uploadBtn) {
    uploadBtn.addEventListener('click', function(e) {
      console.log('Upload button clicked');
      e.preventDefault();
      uploadVideo();
    });
  } else {
    console.error('Upload button not found!');
  }

  // Select video button
  if (selectBtn && fileInput) {
    selectBtn.addEventListener('click', () => {
      console.log('Select button clicked');
      fileInput.click();
    });
  }

  // File input change
  if (fileInput) {
    fileInput.addEventListener('change', function() {
      console.log('File input changed:', this.files[0]);
      const file = this.files[0];
      if (file) {
        console.log('File selected:', file.name, file.size, file.type);
        if (uploadBtn) uploadBtn.disabled = false;
        hideError(); // Hide error when new file is selected
      }
    });
  }

  console.log('Event listeners setup complete');
});
</script>
</body>
</html>