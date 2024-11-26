<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Basic -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UKK Januardi</title>
    <link rel="icon" href="{{ asset('images/fevicon.png') }}" type="image/png" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0; /* Remove default margin */
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-weight: bold;
            color: #333;
        }
        .form-control {
            border-radius: 10px;
            border: 1px solid #ced4da;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #6a11cb;
        }
        .btn-success {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            border: none;
            border-radius: 10px;
        }
        .btn-success:hover {
            background: linear-gradient(to right, #2575fc, #6a11cb);
        }
        @media (max-width: 576px) {
            .card {
                width: 90%; /* Make the card take up more width on small screens */
            }
        }
    </style>
</head>
<body>
    @include('sweetalert::alert')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 col-10"> <!-- Added col-10 for smaller screens -->
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
                            <input type="submit" class="btn btn-success w-100 mt-3" value="Sign In">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>
</html>
