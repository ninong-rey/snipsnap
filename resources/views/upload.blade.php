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
* { margin:0; padding:0; box-sizing:border-box; font-family:'Inter','Segoe UI',sans-serif; }
body { background:#f9f9f9; color:#161823; display:flex; height:100vh; overflow:hidden; }
.container { display:flex; width:100%; }

/* ==== SKELETON LOADER BASE STYLES ==== */
.skeleton {
  background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
  background-size: 400% 100%;
  animation: shimmer 2s infinite ease-in-out;
  border-radius: 4px;
  position: relative;
  overflow: hidden;
}

.skeleton::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
  animation: shimmerSlide 2s infinite ease-in-out;
}

@keyframes shimmer {
  0% { background-position: -200% 0; }
  100% { background-position: 200% 0; }
}

@keyframes shimmerSlide {
  0% { left: -100%; }
  100% { left: 100%; }
}

/* ==== SIDEBAR SKELETON ==== */
.sidebar.skeleton-loading .logo,
.sidebar.skeleton-loading .upload-btn,
.sidebar.skeleton-loading .menu-item {
  position: relative;
  overflow: hidden;
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

/* ==== MAIN CONTENT SKELETON ==== */
.main-content.skeleton-loading .header h1,
.main-content.skeleton-loading .header p,
.main-content.skeleton-loading .upload-area,
.main-content.skeleton-loading .caption-section,
.main-content.skeleton-loading .upload-actions {
  position: relative;
}

/* Hide actual content during skeleton loading */
.main-content.skeleton-loading .header h1,
.main-content.skeleton-loading .header p,
.main-content.skeleton-loading .upload-icon,
.main-content.skeleton-loading .upload-text,
.main-content.skeleton-loading .upload-subtext,
.main-content.skeleton-loading .select-video-btn,
.main-content.skeleton-loading .caption-section h3,
.main-content.skeleton-loading .caption-input,
.main-content.skeleton-loading .cancel-btn,
.main-content.skeleton-loading .upload-submit-btn {
  opacity: 0;
}

/* Skeleton for header */
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
  margin-bottom: 8px;
}

.main-content.skeleton-loading .header p::before {
  content: '';
  position: absolute;
  top: 40px;
  left: 0;
  width: 400px;
  height: 16px;
  background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
  background-size: 400% 100%;
  animation: shimmer 2s infinite ease-in-out;
  border-radius: 4px;
}

/* Skeleton for upload area */
.main-content.skeleton-loading .upload-area::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 80px 40px;
}

.main-content.skeleton-loading .upload-area::after {
  content: '';
  width: 56px;
  height: 56px;
  background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
  background-size: 400% 100%;
  animation: shimmer 2s infinite ease-in-out;
  border-radius: 50%;
  margin-bottom: 18px;
}

.main-content.skeleton-loading .upload-text::before {
  content: '';
  width: 200px;
  height: 20px;
  background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
  background-size: 400% 100%;
  animation: shimmer 2s infinite ease-in-out;
  border-radius: 4px;
  margin-bottom: 6px;
}

.main-content.skeleton-loading .upload-subtext::before {
  content: '';
  width: 300px;
  height: 14px;
  background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
  background-size: 400% 100%;
  animation: shimmer 2s infinite ease-in-out;
  border-radius: 4px;
  margin-bottom: 20px;
}

.main-content.skeleton-loading .select-video-btn::before {
  content: '';
  width: 140px;
  height: 40px;
  background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
  background-size: 400% 100%;
  animation: shimmer 2s infinite ease-in-out;
  border-radius: 8px;
}

/* Skeleton for caption section */
.main-content.skeleton-loading .caption-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  padding: 24px;
}

.main-content.skeleton-loading .caption-section h3::before {
  content: '';
  position: absolute;
  top: 24px;
  left: 24px;
  width: 80px;
  height: 18px;
  background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
  background-size: 400% 100%;
  animation: shimmer 2s infinite ease-in-out;
  border-radius: 4px;
  margin-bottom: 12px;
}

.main-content.skeleton-loading .caption-input::before {
  content: '';
  position: absolute;
  top: 60px;
  left: 24px;
  width: calc(100% - 48px);
  height: 80px;
  background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
  background-size: 400% 100%;
  animation: shimmer 2s infinite ease-in-out;
  border-radius: 6px;
}

/* Skeleton for action buttons */
.main-content.skeleton-loading .upload-actions::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  padding-top: 20px;
}

.main-content.skeleton-loading .cancel-btn::before {
  content: '';
  width: 80px;
  height: 40px;
  background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
  background-size: 400% 100%;
  animation: shimmer 2s infinite ease-in-out;
  border-radius: 6px;
}

.main-content.skeleton-loading .upload-submit-btn::before {
  content: '';
  width: 120px;
  height: 40px;
  background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
  background-size: 400% 100%;
  animation: shimmer 2s infinite ease-in-out;
  border-radius: 6px;
}

/* ==== SIDEBAR ==== */
.sidebar { width:240px; background:#fff; border-right:1px solid #eee; display:flex; flex-direction:column; padding:24px 0; }
.logo { display:flex; align-items:center; gap:10px; font-weight:700; font-size:22px; color:#161823; padding:0 20px 24px; }
.logo img { width:32px; height:32px; }
.upload-btn { margin:0 20px 20px; padding:12px 16px; background:#fe2c55; border:none; border-radius:8px; color:#fff; font-weight:600; font-size:15px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:0.2s ease; }
.upload-btn:hover { background:#e0264c; transform:translateY(-1px); }
.menu-item { padding:12px 20px; display:flex; align-items:center; gap:12px; color:#161823; text-decoration:none; transition:0.2s; }
.menu-item i { width:22px; text-align:center; }
.menu-item:hover, .menu-item.active { background:#f7f7f7; color:#fe2c55; font-weight:600; }

/* ==== MAIN ==== */
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

/* ==== ACTIONS ==== */
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

/* ==== PROGRESS BAR ==== */
#uploadProgressContainer { display:none; margin-top:20px; height:20px; border-radius:8px; overflow:hidden; background:#eee; position:relative; }
#uploadProgressBar { width:0%; height:100%; background:linear-gradient(90deg,#ff0050,#ffb400,#00ffea,#ff0050); background-size:300% 100%; border-radius:8px; transition:width 0.2s ease-out; animation:gradientShift 2s linear infinite; position:relative; z-index:1; }
#uploadProgressBarText { position:absolute; top:0; left:50%; transform:translateX(-50%); height:100%; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:bold; color:#fff; z-index:2; text-shadow:0 0 3px rgba(0,0,0,0.5); }
@keyframes gradientShift { 0% { background-position:0% 0%; } 100% { background-position:100% 0%; } }

.drag-over { border:2px dashed #00f; }

.file-info { background:#f8f9fa; border:1px solid #e9ecef; border-radius:6px; padding:12px; margin-top:15px; display:none; }
.file-info.show { display:block; }
.file-info div { margin:5px 0; font-size:14px; }

@media(max-width:768px){.container{flex-direction:column;}.sidebar{width:100%;flex-direction:row;justify-content:space-around;border-right:none;border-bottom:1px solid #eee;}.main-content{padding:20px;}}
</style>
</head>
<body>
<div class="container">
  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="logo">
      <img src="{{ secure_asset('default-avatar.png') }}" alt="Avatar" onerror="this.style.display='none'">
      SnipSnap Studio
    </div>
    <a href="{{ route('upload') }}" class="upload-btn active"><i class="fas fa-plus"></i> Upload</a>
    <a href="{{ route('my-web') }}" class="menu-item"><i class="fas fa-home"></i> Home</a>
  </div>

  <!-- Main -->
  <div class="main-content" id="mainContent">
    <div class="header">
      <h1>Upload Your Video</h1>
      <p>Share your moments with the SnipSnap community</p>
    </div>

    <div id="successBox" class="upload-success">
      <i class="fas fa-check-circle"></i> Video uploaded successfully!
    </div>

    <div id="errorBox" class="upload-error">
      <i class="fas fa-exclamation-circle"></i> <span id="errorMessage"></span>
    </div>

    <!-- Upload Form -->
    <form id="uploadForm" method="POST" enctype="multipart/form-data" action="{{ route('upload.store') }}">
      @csrf

      <!-- Progress -->
      <div id="uploadProgressContainer">
        <div id="uploadProgressBar"></div>
        <div id="uploadProgressBarText">0%</div>
      </div>

      <!-- Upload Area -->
      <div class="upload-area" id="uploadArea">
        <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
        <div class="upload-text">Select video to upload</div>
        <div class="upload-subtext">or drag and drop your video file</div>
        <div class="upload-info">
          <small>Recommended: MP4 format, under 50MB for faster upload</small>
        </div>
        <button type="button" class="select-video-btn" id="selectVideoBtn">Select Video</button>
        <input type="file" id="videoFile" name="video" class="file-input" accept="video/*">
      </div>

      <!-- File Info -->
      <div id="fileInfo" class="file-info">
        <div><strong>File:</strong> <span id="fileName"></span></div>
        <div><strong>Size:</strong> <span id="fileSize"></span></div>
        <div><strong>Type:</strong> <span id="fileType"></span></div>
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
        <button type="button" class="cancel-btn" id="cancelUploadBtn" style="display:none;">
          <i class="fas fa-times"></i> Cancel Upload
        </button>
        <button type="button" class="cancel-btn" id="backBtn" onclick="window.location.href='{{ route('my-web') }}'">
          <i class="fas fa-arrow-left"></i> Back
        </button>
        <button type="submit" id="uploadSubmitBtn" class="upload-submit-btn" disabled>
          <i class="fas fa-upload"></i> Upload Video
        </button>
      </div>
    </form>
  </div>
</div>

<script>
// ===== UPLOAD CONFIGURATION =====
const UPLOAD_CONFIG = {
  MAX_FILE_SIZE: 50 * 1024 * 1024, // 50MB
  ALLOWED_TYPES: ['video/mp4', 'video/avi', 'video/mov', 'video/wmv', 'video/quicktime'],
  TIMEOUT: 300000 // 5 minutes
};

// ===== GLOBAL VARIABLES =====
let currentXHR = null;
let loaderTimeout;

// ===== SKELETON LOADER FUNCTIONS =====
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
  setTimeout(() => {
    hideSkeleton();
  }, 1500);
}

function initNavigation() {
  const sidebarLinks = document.querySelectorAll('.menu-item');
  sidebarLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      if (this.classList.contains('active') || this.getAttribute('href') === '#') return;
      
      e.preventDefault();
      const targetUrl = this.getAttribute('href');
      
      showSkeleton();
      setTimeout(() => {
        window.location.href = targetUrl;
      }, 500);
    });
  });
}

// ===== HELPER FUNCTIONS =====
function getElement(id) {
  const element = document.getElementById(id);
  if (!element) {
    console.warn(`Element with id '${id}' not found`);
  }
  return element;
}

function formatFileSize(bytes) {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function showError(message) {
  const errorMessage = getElement('errorMessage');
  const errorBox = getElement('errorBox');
  
  if (errorMessage) errorMessage.textContent = message;
  if (errorBox) {
    errorBox.classList.add('show');
    setTimeout(() => {
      errorBox.classList.remove('show');
    }, 5000);
  }
  console.error('Upload Error:', message);
}

function showSuccess() {
  const successBox = getElement('successBox');
  if (successBox) {
    successBox.classList.add('show');
    setTimeout(() => {
      successBox.classList.remove('show');
    }, 3000);
  }
}

function toggleUploadButtons(uploading) {
  const cancelUploadBtn = getElement('cancelUploadBtn');
  const backBtn = getElement('backBtn');
  
  if (cancelUploadBtn) cancelUploadBtn.style.display = uploading ? 'block' : 'none';
  if (backBtn) backBtn.style.display = uploading ? 'none' : 'block';
}

function resetUploadForm() {
  const uploadBtn = getElement('uploadSubmitBtn');
  const progressContainer = getElement('uploadProgressContainer');
  const progressBar = getElement('uploadProgressBar');
  const progressText = getElement('uploadProgressBarText');
  const fileInfo = getElement('fileInfo');
  const previewBox = getElement('videoPreview');
  const fileInput = getElement('videoFile');
  
  if (uploadBtn) {
    uploadBtn.disabled = false;
    uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Upload Video';
  }
  if (progressContainer) progressContainer.style.display = 'none';
  if (progressBar) progressBar.style.width = '0%';
  if (progressText) progressText.textContent = '0%';
  if (fileInfo) fileInfo.style.display = 'none';
  if (previewBox) previewBox.style.display = 'none';
  if (fileInput) fileInput.value = '';
  
  toggleUploadButtons(false);
}

function validateFile(file) {
  if (!file) {
    showError('Please select a file');
    return false;
  }

  if (!UPLOAD_CONFIG.ALLOWED_TYPES.includes(file.type)) {
    showError('Please select a valid video file (MP4, AVI, MOV, WMV)');
    return false;
  }

  if (file.size > UPLOAD_CONFIG.MAX_FILE_SIZE) {
    const maxSizeMB = UPLOAD_CONFIG.MAX_FILE_SIZE / (1024 * 1024);
    showError(`File too large. Maximum size is ${maxSizeMB}MB. Your file is ${formatFileSize(file.size)}`);
    return false;
  }

  return true;
}

function showFileInfo(file) {
  const fileName = getElement('fileName');
  const fileSize = getElement('fileSize');
  const fileType = getElement('fileType');
  const fileInfo = getElement('fileInfo');
  
  if (fileName) fileName.textContent = file.name;
  if (fileSize) fileSize.textContent = formatFileSize(file.size);
  if (fileType) fileType.textContent = file.type;
  if (fileInfo) fileInfo.style.display = 'block';
}

function cancelUpload() {
  if (currentXHR) {
    currentXHR.abort();
    console.log('Upload cancelled by user');
  }
  resetUploadForm();
  showError('Upload cancelled');
}

// ===== UPLOAD EVENT HANDLERS =====
function setupEventListeners() {
  const fileInput = getElement('videoFile');
  const selectBtn = getElement('selectVideoBtn');
  const uploadArea = getElement('uploadArea');
  const uploadForm = getElement('uploadForm');
  const cancelUploadBtn = getElement('cancelUploadBtn');
  const previewBox = getElement('videoPreview');
  const previewVideo = previewBox ? previewBox.querySelector('video') : null;
  const uploadBtn = getElement('uploadSubmitBtn');

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
      console.log('File selected:', this.files[0]);
      const file = this.files[0];
      
      if (file && validateFile(file)) {
        showFileInfo(file);

        // Show preview
        if (previewVideo) {
          previewVideo.src = URL.createObjectURL(file);
        }
        if (previewBox) {
          previewBox.style.display = 'block';
        }

        // Enable upload button
        if (uploadBtn) {
          uploadBtn.disabled = false;
        }

        console.log('File validated and ready for upload');
      } else {
        this.value = '';
        if (uploadBtn) uploadBtn.disabled = true;
      }
    });
  }

  // Drag and drop
  if (uploadArea) {
    uploadArea.addEventListener('dragover', (e) => {
      e.preventDefault();
      uploadArea.classList.add('drag-over');
    });

    uploadArea.addEventListener('dragleave', () => {
      uploadArea.classList.remove('drag-over');
    });

    uploadArea.addEventListener('drop', (e) => {
      e.preventDefault();
      uploadArea.classList.remove('drag-over');
      
      const file = e.dataTransfer.files[0];
      console.log('File dropped:', file);
      
      if (file && validateFile(file)) {
        if (fileInput) {
          fileInput.files = e.dataTransfer.files;
        }
        
        showFileInfo(file);

        // Show preview
        if (previewVideo) {
          previewVideo.src = URL.createObjectURL(file);
        }
        if (previewBox) {
          previewBox.style.display = 'block';
        }

        // Enable upload button
        if (uploadBtn) {
          uploadBtn.disabled = false;
        }

        console.log('Dropped file validated and ready for upload');
      }
    });
  }

  // Form submission
  if (uploadForm) {
    uploadForm.addEventListener('submit', function(e) {
      e.preventDefault();
      console.log('Form submission started');

      const fileInput = getElement('videoFile');
      const file = fileInput ? fileInput.files[0] : null;
      
      if (!file) {
        showError('Please select a video file first');
        return;
      }

      if (!validateFile(file)) {
        return;
      }

      console.log('Starting upload process for file:', file.name);

      // Update UI
      const uploadBtn = getElement('uploadSubmitBtn');
      const progressContainer = getElement('uploadProgressContainer');
      const progressBar = getElement('uploadProgressBar');
      const progressText = getElement('uploadProgressBarText');
      
      if (uploadBtn) {
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
      }
      if (progressContainer) progressContainer.style.display = 'block';
      if (progressBar) progressBar.style.width = '0%';
      if (progressText) progressText.textContent = '0%';
      
      toggleUploadButtons(true);

      const formData = new FormData(this);
      
      // Log FormData contents for debugging
      for (let [key, value] of formData.entries()) {
        console.log('FormData:', key, value instanceof File ? value.name : value);
      }

      currentXHR = new XMLHttpRequest();
      currentXHR.timeout = UPLOAD_CONFIG.TIMEOUT;

      currentXHR.open('POST', this.action, true);
      currentXHR.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
      currentXHR.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

      // Progress tracking
      currentXHR.upload.addEventListener('progress', (e) => {
        if (e.lengthComputable) {
          const percent = Math.round((e.loaded / e.total) * 100);
          const progressBar = getElement('uploadProgressBar');
          const progressText = getElement('uploadProgressBarText');
          
          if (progressBar) progressBar.style.width = percent + '%';
          if (progressText) progressText.textContent = percent + '%';
          
          console.log(`Upload progress: ${percent}%`);
        }
      });

      currentXHR.onload = function() {
        console.log('Upload response received. Status:', currentXHR.status);
        console.log('Response:', currentXHR.responseText);

        if (currentXHR.status === 200) {
          try {
            const response = JSON.parse(currentXHR.responseText);
            if (response.success) {
              showSuccess();
              console.log('Upload successful, redirecting...');
              setTimeout(() => {
                window.location.href = "{{ route('my-web') }}?uploaded_video=" + encodeURIComponent(response.url);
              }, 1500);
            } else {
              showError('Upload failed: ' + (response.message || 'Unknown error'));
              resetUploadForm();
            }
          } catch (e) {
            console.error('JSON parse error:', e);
            showError('Error processing server response');
            resetUploadForm();
          }
        } else {
          showError('Server error: ' + currentXHR.status);
          resetUploadForm();
        }
      };

      currentXHR.onerror = function() {
        console.error('Network error during upload');
        showError('Network error. Please check your connection.');
        resetUploadForm();
      };

      currentXHR.ontimeout = function() {
        console.error('Upload timeout');
        showError('Upload timed out. Please try again.');
        resetUploadForm();
      };

      // Start the upload
      console.log('Sending upload request...');
      currentXHR.send(formData);
    });
  }

  // Cancel upload
  if (cancelUploadBtn) {
    cancelUploadBtn.addEventListener('click', cancelUpload);
  }
}

// ===== INITIALIZATION =====
document.addEventListener('DOMContentLoaded', function() {
  console.log('Initializing upload page...');
  
  // Initialize skeleton loader
  initSkeletonLoader();
  
  // Setup event listeners
  setupEventListeners();
  
  // Initialize navigation
  initNavigation();
  
  console.log('Upload page initialized successfully');
});

// Clean up object URLs when leaving the page
window.addEventListener('beforeunload', function() {
  const previewBox = getElement('videoPreview');
  const previewVideo = previewBox ? previewBox.querySelector('video') : null;
  
  if (previewVideo && previewVideo.src) {
    URL.revokeObjectURL(previewVideo.src);
  }
});
</script>
</body>
</html>