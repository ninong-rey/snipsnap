<!DOCTYPE html>
<html>
<head>
    <title>Video Debug - Comprehensive</title>
</head>
<body>
    <h2>Comprehensive Video Debug</h2>
    <div id="results"></div>

    <script>
    console.log('=== COMPREHENSIVE VIDEO DEBUG ===');
    
    let results = '';
    
    // 1. Check all video elements
    const videos = document.querySelectorAll('video');
    results += '<h3>1. Video Elements Found: ' + videos.length + '</h3>';
    
    videos.forEach((video, index) => {
        const sources = video.querySelectorAll('source');
        results += `
            <div style="border: 1px solid #ccc; margin: 10px; padding: 10px;">
                <h4>Video ${index}</h4>
                <p><strong>src:</strong> ${video.src}</p>
                <p><strong>currentSrc:</strong> ${video.currentSrc}</p>
                <p><strong>dataset:</strong> <pre>${JSON.stringify(video.dataset, null, 2)}</pre></p>
                <p><strong>Sources (${sources.length}):</strong></p>
                ${Array.from(sources).map((source, i) => 
                    `<p>Source ${i}: ${source.src} (type: ${source.type})</p>`
                ).join('')}
            </div>
        `;
        
        console.log('Video ' + index, {
            src: video.src,
            currentSrc: video.currentSrc,
            dataset: video.dataset,
            sources: Array.from(sources).map(s => s.src)
        });
    });
    
    // 2. Check for video URLs in data attributes
    const elementsWithVideoData = document.querySelectorAll('[data-src*=".mp4"], [data-video*=".mp4"]');
    results += '<h3>2. Elements with Video Data Attributes: ' + elementsWithVideoData.length + '</h3>';
    
    elementsWithVideoData.forEach((el, index) => {
        results += `
            <div style="border: 1px solid #orange; margin: 10px; padding: 10px;">
                <h4>Element ${index}</h4>
                <p><strong>Tag:</strong> ${el.tagName}</p>
                <p><strong>data-src:</strong> ${el.dataset.src || 'N/A'}</p>
                <p><strong>data-video:</strong> ${el.dataset.video || 'N/A'}</p>
                <p><strong>Outer HTML:</strong> <pre>${el.outerHTML.substring(0, 500)}</pre></p>
            </div>
        `;
    });
    
    // 3. Check localStorage and sessionStorage for video data
    results += '<h3>3. Browser Storage Check</h3>';
    
    let storageVideoData = [];
    for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        const value = localStorage.getItem(key);
        if (value && value.includes('.mp4')) {
            storageVideoData.push({key, value: value.substring(0, 200)});
        }
    }
    
    results += '<p>LocalStorage with .mp4: ' + storageVideoData.length + '</p>';
    storageVideoData.forEach(item => {
        results += `<p><strong>${item.key}:</strong> ${item.value}</p>`;
    });
    
    // 4. Check if there are any API calls that return video data
    results += '<h3>4. Monitoring API Calls</h3>';
    results += '<p id="apiCalls">No API calls detected yet...</p>';
    
    // Override fetch to monitor API calls
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        return originalFetch.apply(this, args).then(response => {
            if (args[0] && typeof args[0] === 'string' && 
                (args[0].includes('/api/') || args[0].includes('/videos/') || args[0].includes('/web'))) {
                
                response.clone().json().then(data => {
                    console.log('API Call:', args[0], data);
                    if (JSON.stringify(data).includes('.mp4')) {
                        document.getElementById('apiCalls').innerHTML += 
                            `<br>API: ${args[0]} returned video data`;
                    }
                }).catch(() => {});
            }
            return response;
        });
    };
    
    document.getElementById('results').innerHTML = results;
    
    console.log('Debug complete - check results above');
    </script>
</body>
</html>