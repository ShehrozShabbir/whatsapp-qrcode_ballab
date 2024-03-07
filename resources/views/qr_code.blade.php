<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp QR Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container-fluid {
            text-align: center;
        }
        .qr-code {
            max-width: 100%; /* Adjust the maximum width of the QR code */
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h1>WhatsApp QR Caode</h1>
        <img class="qr-code" src="data:image/png;base64,{{ base64_encode($imageData) }}" alt="QR Code">
    </div>
    <script>
        // Reload the page after 10 seconds
        setInterval(function() {
            updateQrCode();
        }, 5000); // 100000 milliseconds = 1  minute
    </script>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
       function updateQrCode(){
        $.ajax({
            url: "/update-qrcode",
            method: "post",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            dataType: "json",
            data: {
                action: 'update',

            },
            success: function (response) {
                console.log(response);
                if(response.status=="success"){
                    console.log('updated qr');
                    $('.qr-code').attr('src','');
                    $('.qr-code').attr('src','data:image/png;base64,'+response.message);
                }else{
                    updateQrCode();
                }

            },
        });
       }
</script>
