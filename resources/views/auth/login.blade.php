<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        RetailOps Login
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>

        body {
            min-height: 100vh;
            background:
                linear-gradient(
                    135deg,
                    #0f172a,
                    #1e293b,
                    #2563eb
                );
            font-family: Inter, Arial, sans-serif;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-card {
            width: 100%;
            max-width: 460px;
            border: none;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,0.25);
        }

        .login-header {
            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #1d4ed8
                );
            color: white;
            padding: 34px;
            text-align: center;
        }

        .login-header h1 {
            font-weight: 800;
            margin-bottom: 6px;
        }

        .login-body {
            padding: 36px;
            background: white;
        }

        .form-control {
            border-radius: 14px;
            padding: 12px 14px;
        }

        .btn {
            border-radius: 14px;
            padding: 12px;
            font-weight: 700;
        }

        .brand-icon {
            width: 58px;
            height: 58px;
            border-radius: 18px;
            background: rgba(255,255,255,0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            font-size: 26px;
        }

    </style>

</head>

<body>

<div class="login-wrapper">

    <div class="card login-card">

        <div class="login-header">

            <div class="brand-icon">
                <i class="bi bi-shop"></i>
            </div>

            <h1>
                RetailOps
            </h1>

            <p class="mb-0">
                Retail Fraud & Analytics Platform
            </p>

        </div>

        <div class="login-body">

            @if(session('status'))

                <div class="alert alert-success">
                    {{ session('status') }}
                </div>

            @endif

            <form
                method="POST"
                action="{{ route('login') }}"
            >

                @csrf

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Enter your email"
                    >

                    @error('email')

                        <div class="text-danger small mt-2">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    >

                    @error('password')

                        <div class="text-danger small mt-2">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="remember"
                            id="remember"
                        >

                        <label
                            class="form-check-label"
                            for="remember"
                        >
                            Remember me
                        </label>

                    </div>

                    @if(Route::has('password.request'))

                        <a
                            href="{{ route('password.request') }}"
                            class="small text-decoration-none"
                        >
                            Forgot Password?
                        </a>

                    @endif

                </div>

                <button
                    type="submit"
                    class="btn btn-primary w-100"
                >
                    <i class="bi bi-box-arrow-in-right"></i>
                    Login
                </button>

            </form>

        </div>

    </div>

</div>

</body>

</html>