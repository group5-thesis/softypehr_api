<!DOCTYPE html>

<head>
    <title>Pusher Test</title>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('a76305e0740371c8f208', {
        cluster: 'ap1'
    });

    var channel = pusher.subscribe('softypeChannel');
    channel.bind('message', function(data) {
        alert(JSON.stringify(data));
    });
    </script>
</head>

<body>
    <h1>Pusher Test</h1>
    <p>
        Try publishing an event to channel <code>softypeChannel</code>
        with event name <code>message</code>.
    </p>
</body>