<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; background:#0b1020; color:#e5e7eb; display:flex; align-items:center; justify-content:center; height:100vh; margin:0; }
        .card { background:#111827; padding:2rem; border-radius:12px; width:100%; max-width:380px; box-shadow:0 10px 30px rgba(0,0,0,.3); }
        h1 { font-size:1.25rem; margin-bottom:1rem; color:#fbbf24; }
        label { display:block; font-size:.875rem; margin-top:.75rem; color:#9ca3af; }
        input[type=email], input[type=password] { width:100%; padding:.75rem; border-radius:8px; border:1px solid #374151; background:#0b1020; color:#e5e7eb; margin-top:.25rem; box-sizing:border-box; }
        .row { display:flex; align-items:center; justify-content:space-between; margin-top:.75rem; font-size:.875rem; }
        .btn { width:100%; margin-top:1rem; padding:.75rem; background:#fbbf24; color:#111827; border:none; border-radius:8px; cursor:pointer; font-weight:600; }
        .btn:hover { background:#f59e0b; }
        .error { color:#ef4444; font-size:.875rem; margin-top:.5rem; }
        a { color:#93c5fd; text-decoration:none; }
        a:hover { text-decoration:underline; }
    </style>
</head>
<body>
<div class="card">
    <h1>Entrar</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required autofocus value="{{ old('email') }}">
        @error('email')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="password">Senha</label>
        <input type="password" id="password" name="password" required>
        @error('password')
            <div class="error">{{ $message }}</div>
        @enderror

        <div class="row">
            <label style="display:flex; gap:.5rem; align-items:center;">
                <input type="checkbox" name="remember" value="1"> Manter conectado
            </label>
            <a href="#" onclick="alert('Recuperação de senha não implementada.'); return false;">Esqueci a senha</a>
        </div>

        <button class="btn" type="submit">Entrar</button>
    </form>
</div>
</body>
</html>

