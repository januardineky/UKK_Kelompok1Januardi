<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Basic -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Bootstrap 5 Admin Panel Template</title>
    <link rel="icon" href="{{ asset('images/fevicon.png') }}" type="image/png" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('ceess/bootstrap.min.css') }}" />
    <!-- Custom CSS -->
    {{-- <link rel="stylesheet" href="style.css" /> --}}
</head>
<body class="d-flex bg-light">
    <div class="container">
        <div class="row justify-content-center" style="margin-top: 120px">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Sign In</h5>
                        <form action="/auth" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="E-mail" required />
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required />
                            </div>
                            <input type="submit" class="btn btn-success w-100 mt-3"></input>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="{{ asset('jees/bootstrap.min.js') }}"></script>
</body>
</html>
