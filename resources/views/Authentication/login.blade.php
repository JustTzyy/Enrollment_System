<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMIANKonek | Login Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
        </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('/css/login.css') }}">
</head>

<body>

    <div class="container">

        <div class="modal fade" id="welcomeModal" tabindex="-1">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-body  ">
                        <div class="col-md-6 d-flex justify-content-center ">
                            <img src="{{ asset('/image/modal.png') }}" class="modal-img mt-2" alt="logo">
                        </div>
                        <div class="col modal-text mb-3 mt-3">
                            <p>Welcome to UMIANKonek, where enrolling for your Senior High School journey is
                                made effortless, efficient, and exciting! Say goodbye to long lines and
                                paperwork—here, you can easily register, choose your courses, and connect with
                                your school community in just a few clicks.</p>
                        </div>

                        <button type="button" class="btn btn-modal" data-bs-dismiss="modal">Continue</button>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col  d-flex justify-content-center">
                <div class="card d-flex justify-content-center align-items-center">
                    <img src="{{ asset('/image/UMIANKonek.png') }}" class="card-img-top mt-5" alt="logo">
                    <div class="card-body w-75 ">
                        <h2 class="card-title mb-3">Main Campus</h2>

                        <!-- Mao ni akong gi ilisan -->

                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-floating mb-4">
                                <input type="text" name="login" class="form-control" id="input"
                                    placeholder="Student ID or Email" required>
                                <label for="input">Student ID Number or Email</label>
                            </div>

                            <div class="form-floating mb-2 ">
                                <input type="password" name="password" class="form-control" id="inputPassword"
                                    placeholder="Password" autocomplete="current-password" required>
                                <label for="inputPassword">Password</label>
                            </div>

                            <button type="submit" class="btn mt-3 mb-3 w-50">Login</button>
                        </form>

                        <a class="forgotpass" href="#" role="button">Forgot Password?</a>

                        @if(session('error'))
                            <div class="alert alert-danger mb-5">{{ session('error') }}</div>
                        @endif


                    </div>
                </div>
            </div>

        </div>

</body>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var myModal = new bootstrap.Modal(document.getElementById('welcomeModal'));
        myModal.show();
    });
</script>

</html>