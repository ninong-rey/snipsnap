<!DOCTYPE html>
<html>
<head>
    <title>Video Debug</title>
</head>
<body>
    <h2>Video Source Debug</h2>
    <div id="results"></div>

    <script>
    console.log('=== VIDEO DEBUG START ===');
    
    let results = '<h3>1. Video Elements:</h3>';
    const videos = document.querySelectorAll('video');
    results += `<p>Found: ${videos.length} video elements</p>`;
    
    videos.forEach((video, index) => {
        results += `
            <div style="border:1px solid #ccc; margin:10px; padding:10px;">
                <h4>Video ${index}</h4>
                <p><strong>src:</strong> ${video.src}</p>
                <p><strong>currentSrc:</strong> ${video.currentSrc}</p>
                <p><strong>Parent HTML:</strong> <code>${video.parentElement?.outerHTML?.substring(0, 500)}</code></p>
            </div>
        `;
        console.log(`Video ${index}:`, video.src, video.currentSrc);
    });
    
    // Check for elements with data-src
    results += '<h3>2. Elements with data-src:</h3>';
    const dataSrcElements = document.querySelectorAll('[data-src]');
    results += `<p>Found: ${dataSrcElements.length} elements with data-src</p>`;
    
    dataSrcElements.forEach((el, index) => {
        results += `
            <div style="border:1px solid orange; margin:10px; padding:10px;">
                <p><strong>Element ${index}:</strong> ${el.tagName}</p>
                <p><strong>data-src:</strong> ${el.dataset.src}</p>
            </div>
        `;
    });
    
    document.getElementById('results').innerHTML = results;
    </script>
</body>
</html>