<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — Islamic Online Madrasah</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#0f2440 0%,#1a5276 50%,#0f2440 100%)}
.login-card{background:#fff;border-radius:16px;padding:40px;width:100%;max-width:400px;box-shadow:0 25px 60px rgba(0,0,0,.3)}
.login-logo{text-align:center;margin-bottom:28px}
.login-logo img{width:70px;height:70px;border-radius:12px;object-fit:cover}
.login-logo h1{font-size:18px;font-weight:700;color:#0f2440;margin-top:12px;line-height:1.3}
.login-logo p{font-size:12px;color:#718096;margin-top:4px}
.form-group{margin-bottom:16px}
.form-label{display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px}
.form-control{width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:9px;font-size:14px;color:#1a202c;transition:.2s;font-family:inherit}
.form-control:focus{outline:none;border-color:#1a5276;box-shadow:0 0 0 3px rgba(26,82,118,.15)}
.form-control.is-invalid{border-color:#dc2626}
.invalid-feedback{color:#dc2626;font-size:12px;margin-top:4px}
.remember-row{display:flex;align-items:center;gap:8px;margin-bottom:20px;font-size:13px;color:#4a5568}
.btn-login{width:100%;padding:11px;background:linear-gradient(135deg,#1a3a5c,#1a5276);color:#fff;border:none;border-radius:9px;font-size:14px;font-weight:700;cursor:pointer;transition:.2s;font-family:inherit}
.btn-login:hover{opacity:.9;transform:translateY(-1px)}
.login-footer{text-align:center;margin-top:20px;font-size:12px;color:#9ca3af}
.login-footer a{color:#1a5276;text-decoration:none;font-weight:600}
</style>
</head>
<body>
<div class="login-card">
  <div class="login-logo">
    <img src="{{ asset('uploads/logos/default.png') }}" alt="IOM Logo" onerror="this.style.display='none'">
    <h1>Islamic Online Madrasah</h1>
    <p>অ্যাকাউন্টে প্রবেশ করুন</p>
  </div>

  <form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
      <label class="form-label" for="email">ইমেইল</label>
      <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
             value="{{ old('email') }}" required autofocus placeholder="your@email.com">
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="form-group">
      <label class="form-label" for="password">পাসওয়ার্ড</label>
      <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••">
    </div>

    <div class="remember-row">
      <input type="checkbox" id="remember" name="remember">
      <label for="remember">মনে রাখুন</label>
    </div>

    <button type="submit" class="btn-login">লগইন করুন →</button>
  </form>

  <div class="login-footer">
    <p>ভর্তি হতে চান? <a href="{{ route('admission.form') }}">এখানে আবেদন করুন</a></p>
  </div>
</div>
</body>
</html>
