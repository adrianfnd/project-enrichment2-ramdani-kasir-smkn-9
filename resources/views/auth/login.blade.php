<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>KASIR SMKN 9 BANDUNG</title>
        <meta
          content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
          name="viewport"
        />
        <link
          rel="icon"
          href="{{ asset('assets/img/kaiadmin/Logonine.png') }}"
          type="image/x-icon"
        />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <style>
        body {
            background: linear-gradient(to right, rgb(255, 255, 255), #3949AB);
            font-family: 'Roboto', sans-serif;
            color: #fff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .card {
            background: rgb(38, 17, 77);
            background-size: cover;
            color: #000;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 250px;
            width: 100%;
            position: relative;
        }

        .card-logo {
            position: absolute;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 100px;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-logo img {
            width: 70%;
            height: auto;
        }

        .card-header {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            margin-top: 50px;
            color: #fff;
        }

        .form-control {
            border-radius: 25px;
            max-width: 100%;
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        .btn-primary {
            background-color: #92a1ff;
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 1rem;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #3949AB;
        }

        .form-check-label {
            margin-left: 5px;
            color: #fff;
        }

        .form-group label {
            color: #fff;
        }

        .btn-link {
            color: #1A237E;
            text-decoration: none;
            transition: color 0.3s;
        }

        .btn-link:hover {
            color: #3949AB;
        }

        .text-center {
            text-align: center;
        }
    </style>

    <body>
        <div class="card">
            <div class="card-logo">
                <img src="{{ asset('assets/img/kaiadmin/Logonine.png') }}" alt="Logo">
            </div>
            <div class="card-header">Login</div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">E-Mail Address</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                    </div>
                    @error('email')
                    <script>
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: '{{ $message }}',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    </script>
                    @enderror
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    </body>
</html>
