<!DOCTYPE html>
<html>
<head>
    <title>Join Call - SnipSnap</title>
    <script src='https://meet.jit.si/external_api.js'></script>
    <style>
        body { 
            margin: 0; 
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #000;
        }
        #jitsiContainer { 
            width: 100vw; 
            height: 100vh; 
        }
        .loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-align: center;
        }
    </style>
</head>
<body>
    <div id="jitsiContainer"></div>
    <div class="loading" id="loading">
        <h2>Joining Call...</h2>
        <p>Please wait while we connect you.</p>
    </div>
    
    <script>
        const roomId = "{{ $roomId }}";
        const displayName = "{{ Auth::user()->name }}";
        
        const api = new JitsiMeetExternalAPI('meet.jit.si', {
            roomName: roomId,
            width: '100%',
            height: '100%',
            parentNode: document.getElementById('jitsiContainer'),
            userInfo: { 
                displayName: displayName 
            },
            configOverwrite: {
                prejoinPageEnabled: false,
                startWithAudioMuted: false,
                startWithVideoMuted: false
            }
        });
        
        api.addEventListener('videoConferenceJoined', () => {
            document.getElementById('loading').style.display = 'none';
        });
        
        // Close window when call ends
        api.addEventListener('videoConferenceLeft', () => {
            setTimeout(() => {
                window.close();
            }, 1000);
        });
    </script>
</body>
</html>