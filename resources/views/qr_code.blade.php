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
        .alert-dark{
            background: #000;
            color: #fff;
        }
        .dnone{
            display: none;
        }

    </style>
</head>

<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark d-flex justify-content-between">
            <a class="navbar-brand" href="#">{{auth()->user()->email}}</a>
            <div>
                <a href="{{route('logoutSession')}}"  class="btn btn-outline-primary my-2 my-sm-0">Logout from Whatsapp</a>
            <a href="{{route('logout')}}" class="btn btn-outline-success my-2 my-sm-0" >Logout</a>
            </div>
          </nav>
        @if ($status == 200 OR $status == 201)

            @if ($status==201)
            <img class="qr-code" src="data:image/png;base64,{{$imageData}}" alt="QR Code">
            @else
            <img class="qr-code dnone" style="" alt="QR Code">
            <div class="vh-100 d-flex align-items-center justify-content-center">
                <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
              </div>

            @endif


        @else
        <img class="qr-code dnone" style="" alt="QR Code">
         <div class="row mt-5">
            <div class=" col-sm-7 mt-5 mx-auto" >
                <div class="alert alert-dark" role="alert">
                   {{$errorMessage}}
                  </div>
            </div>
         </div>


        @endif

    </div>

</body>

</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    // Reload the page after 10 seconds

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
