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

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: Inter, Arial, sans-serif;
            background: #f3f4f6;
            overflow-x: hidden;
        }

        .login-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
        }

        .login-brand-panel {
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, 0.35), transparent 32%),
                linear-gradient(180deg, #0f172a, #1e293b);
            color: white;
            padding: 54px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .login-brand-panel::after {
            content: "";
            position: absolute;
            width: 420px;
            height: 420px;
            right: -160px;
            bottom: -160px;
            background: rgba(37, 99, 235, 0.22);
            border-radius: 999px;
            filter: blur(4px);
        }

        .brand-logo {
            font-size: 30px;
            font-weight: 800;
            letter-spacing: 0.4px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 30px rgba(37, 99, 235, 0.35);
        }

        .hero-title {
            font-size: 48px;
            line-height: 1.08;
            font-weight: 900;
            margin-bottom: 18px;
            max-width: 620px;
        }

        .hero-subtitle {
            color: #cbd5e1;
            font-size: 17px;
            max-width: 560px;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 32px;
            position: relative;
            z-index: 2;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 22px;
            padding: 18px;
            backdrop-filter: blur(10px);
        }

        .feature-card i {
            color: #60a5fa;
            font-size: 22px;
        }

        .feature-card .label {
            margin-top: 10px;
            font-weight: 800;
        }

        .feature-card .desc {
            color: #cbd5e1;
            font-size: 13px;
            margin-top: 4px;
        }

        .login-form-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 42px;
            background:
                radial-gradient(circle at top right, rgba(37, 99, 235, 0.12), transparent 28%),
                #f3f4f6;
        }

        .login-card {
            width: 100%;
            max-width: 500px;
            border: none;
            border-radius: 28px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.12);
            overflow: hidden;
        }

        .login-card-header {
            background: white;
            padding: 34px 36px 10px;
        }

        .login-card-header h2 {
            font-size: 32px;
            font-weight: 900;
            margin-bottom: 6px;
        }

        .login-card-header p {
            color: #6b7280;
            margin-bottom: 0;
        }

        .login-card-body {
            background: white;
            padding: 30px 36px 36px;
        }

        .form-label {
            font-weight: 700;
            color: #111827;
        }

        .input-group-text {
            border-radius: 16px 0 0 16px;
            background: #f9fafb;
            border-right: none;
        }

        .form-control {
            border-radius: 16px;
            padding: 13px 14px;
            border: 1px solid #d1d5db;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 16px 16px 0;
        }

        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.14);
            border-color: #2563eb;
        }

        .btn-login {
            border-radius: 16px;
            padding: 13px;
            font-weight: 800;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            box-shadow: 0 14px 30px rgba(37, 99, 235, 0.25);
        }

        .mini-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 24px;
        }

        .mini-card {
            background: #f9fafb;
            border-radius: 18px;
            padding: 14px;
            text-align: center;
        }

        .mini-card .value {
            font-size: 21px;
            font-weight: 900;
            color: #111827;
        }

        .mini-card .text {
            font-size: 12px;
            color: #6b7280;
        }

        .secure-note {
            color: #6b7280;
            font-size: 13px;
            margin-top: 22px;
            display: flex;
            gap: 8px;
            align-items: center;
            justify-content: center;
        }

        @media(max-width: 992px) {
            .login-page {
                grid-template-columns: 1fr;
            }

            .login-brand-panel {
                padding: 34px;
            }

            .hero-title {
                font-size: 36px;
            }

            .login-form-panel {
                padding: 24px;
            }
        }

    </style>

</head>

<body>

<div class="login-page">

    <div class="login-brand-panel">

        <div class="brand-logo">

            <div class="brand-logo-icon">
                <i class="bi bi-grid-fill"></i>
            </div>

            RetailOps

        </div>

        <div style="position: relative; z-index: 2;">

            <div class="hero-title">
                Retail intelligence for smarter operations.
            </div>

            <p class="hero-subtitle">
                Monitor sales, inventory, customer risk, fraud patterns, investigation cases, and executive insights from one dashboard.
            </p>

            <div class="feature-grid">

                <div class="feature-card">
                    <i class="bi bi-shield-check"></i>
                    <div class="label">Fraud Control</div>
                    <div class="desc">Risk flags, scenario rules, and investigation workflow.</div>
                </div>

                <div class="feature-card">
                    <i class="bi bi-graph-up-arrow"></i>
                    <div class="label">Analytics</div>
                    <div class="desc">Revenue, store ranking, rule analytics, and trends.</div>
                </div>

                <div class="feature-card">
                    <i class="bi bi-box-seam"></i>
                    <div class="label">Inventory</div>
                    <div class="desc">Low stock alerts and inventory forecast prediction.</div>
                </div>

                <div class="feature-card">
                    <i class="bi bi-robot"></i>
                    <div class="label">Local Copilot</div>
                    <div class="desc">Ask about risk, stock, revenue, store, and customers.</div>
                </div>

            </div>

        </div>

        <div style="position: relative; z-index: 2; color:#94a3b8; font-size:13px;">
            © {{ date('Y') }} RetailOps Management Platform
        </div>

    </div>

    <div class="login-form-panel">

        <div class="card login-card">

            <div class="login-card-header">

                <span class="badge bg-dark mb-3">
                    Secure Access
                </span>

                <h2>
                    Welcome Back
                </h2>

                <p>
                    Sign in to continue to your RetailOps dashboard.
                </p>

            </div>

            <div class="login-card-body">

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

                        <label class="form-label">
                            Email Address
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="admin@example.com"
                            >

                        </div>

                        @error('email')

                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Password
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required
                                autocomplete="current-password"
                                placeholder="Enter password"
                            >

                        </div>

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
                                class="small text-decoration-none fw-semibold"
                            >
                                Forgot Password?
                            </a>

                        @endif

                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary btn-login w-100"
                    >
                        <i class="bi bi-box-arrow-in-right"></i>
                        Login to Dashboard
                    </button>

                </form>

                <div class="mini-summary">

                    <div class="mini-card">
                        <div class="value">
                            KPI
                        </div>
                        <div class="text">
                            Dashboard
                        </div>
                    </div>

                    <div class="mini-card">
                        <div class="value">
                            Risk
                        </div>
                        <div class="text">
                            Engine
                        </div>
                    </div>

                    <div class="mini-card">
                        <div class="value">
                            AI
                        </div>
                        <div class="text">
                            Copilot
                        </div>
                    </div>

                </div>

                <div class="secure-note">
                    <i class="bi bi-lock-fill"></i>
                    Protected admin and staff access
                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>