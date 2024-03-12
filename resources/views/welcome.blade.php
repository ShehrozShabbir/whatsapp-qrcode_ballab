<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;

            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: darkslategray;
        }
        .btn-primary,.alert-dark{
            background: darkslategray;
            border-color: darkslategray;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">

     <div class="row">
        <div class="col-sm-5 mx-auto">
            <div class="card">

                <form action="{{route('login')}}" method="post">
                    @csrf
                    <div class="card-body">
                        <h2 class="w-100 text-center my-3">Login </h2>
                        @if (session('error'))
                        <div class="alert alert-dark">
                            {{session('error')}}
                          </div>
                        @endif
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label for="">Email</label>
                                <input type="email" class="form-control" name="email" id="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label for="">Password</label>
                                <input type="password" class="form-control" name="password" id="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                               <button type="submit" class="float-right btn btn-primary">Login</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

     </div>
    </div>

</body>

</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
