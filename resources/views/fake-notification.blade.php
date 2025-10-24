<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fake Notification Test</title>
</head>
<body>
    <h1>Fake Notification Test</h1>
    <p>If you allow notifications, you should see a notification every 5 seconds.</p>

    <script>
        // Request permission on page load
        if ("Notification" in window) {
            Notification.requestPermission().then(function(permission) {
                if (permission !== "granted") {
                    alert("Please allow notifications for testing.");
                }
            });
        }

        function showFakeNotification() {
            if (Notification.permission === "granted") {
                new Notification("Test Notification", {
                    body: "This is a fake notification for testing!",
                    icon: "{{ asset('images/icon.png') }}"

                });
            }
        }

        // Show a notification every 5 seconds
        setInterval(showFakeNotification, 5000);
    </script>
</body>
</html>
