<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <title>WhatsApp QR Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;

            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        img{
            display: flex;
            justify-content: center;
        }

        .qr-code {
            max-width: 100%;
            /* Adjust the maximum width of the QR code */
            margin: auto;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        @if ($status == 200)
            <div class="alert alert-dark d-flex  align-items-center">
                <h1>Generating Qr Code</h1>
                <div class="spinner-grow text-secondary ml-4" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                  <div class="spinner-grow text-secondary" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                  <div class="spinner-grow text-dark" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                  <div class="spinner-grow text-dark" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
            </div>
            <img class="qr-code" src="" alt="QR Code">
            <script>
                setInterval(function() {
                    //updateQrCode();
                }, 5000);
            </script>
        @else
            <div class="alert alert-dark ">
                <h1>{{ $errorMessage }}</h1>

            </div>
        @endif

    </div>

</body>

</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    // Reload the page after 10 seconds
    $('.qr-code').hide();
    updateQrCode();

    function updateQrCode() {
        $.ajax({
            url: "/update-qrcode",
            method: "post",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            dataType: "json",
            data: {
                action: '{!! $qr_key !!}',

            },
            success: function(response) {
                console.log(response);
                if (response.status == "success") {
                    $('.alert-dark').fadeOut();
                    $('.qr-code').show();
                    console.log('updated qr');
                    $('.qr-code').attr('src', '');
                    $('.qr-code').attr('src', 'data:image/png;base64,' + response.message);
                    setTimeout(() => {
                        updateQrCode();
                    }, 5000);
                } else {
                    setTimeout(() => {
                        updateQrCode();
                    }, 2000);
                }

            },
        });
    }
</script>
