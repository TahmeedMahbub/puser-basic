<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="chat">
        <div class="top">
            <img src="https://tinyjpg.com/images/social/website.jpg" width="100px" alt="">
            <div>
                <p>Tahmeed</p>
                <small>Online</small>
            </div>
        </div>

        <div class="messages">
            @include('receive', ['message' => 'Hey! Whats up!'])
        </div>

        <div class="bottom">
            <form>
                <input type="text" id="message" name="message" placeholder="Enter Message">
                <button type="submit"></button>
            </form>
        </div>
    </div>
</body>
<script>
    const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {cluster: 'mt1'})
    const channel = pusher.subscribe('public');

    // RECEIVE MESSAGES
    channel.bind('chat', function(data){
        $.post("/receive", {
            _token: '{{ csrf_token() }}',
            message: data.message,
        }).done(function (res){
            $(".messages > .message").last().after(res);
            $(document).scrollTop($(document).height());
        });
    });

    // BROADCAST MESSAGES
    $("form").submit(function(event) {
        event.preventDefault();

        $.ajax({
            type: "post",
            url: "/broadcast",
            headers: {
                'X-Socket-Id': pusher.connection.socket_id
            },
            data: {
                _token: '{{ csrf_token() }}',
                message: $("form #message").val(),
            }
        }).done(function (res){
            $(".messages > .message").last().after(res);
            $("form #message").val('');
            $(document).scrollTop($(document).height());
        });
    });
</script>
</html>
