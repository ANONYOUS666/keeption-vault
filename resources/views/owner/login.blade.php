<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Command Access</title>
<link href="https://fonts.googleapis.com/css2?family=Clash+Display:wght@700&family=Epilogue:wght@400;500&display=swap" rel="stylesheet"/>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:Epilogue,sans-serif;background:#050810;color:#eef0f6;min-height:100vh;display:flex;align-items:center;justify-content:center;}
.card{background:#0a0d18;border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:44px;width:100%;max-width:400px;}
h1{font-family:'Clash Display',sans-serif;font-size:1.6rem;font-weight:700;margin-bottom:6px;color:#00f5a0;}
.sub{color:#6b7280;font-size:.85rem;margin-bottom:32px;}
.field{margin-bottom:16px;}
.field label{display:block;font-size:.78rem;color:#6b7280;margin-bottom:6px;}
.field input{width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:10px;padding:12px 16px;color:#eef0f6;font-size:.9rem;outline:none;font-family:Epilogue,sans-serif;}
.field input:focus{border-color:rgba(0,245,160,.4);}
.btn{width:100%;padding:13px;background:#00f5a0;border:none;border-radius:10px;color:#04060a;font-family:'Clash Display',sans-serif;font-weight:700;font-size:.95rem;cursor:pointer;margin-top:8px;}
.btn:hover{opacity:.88;}
.error{background:rgba(255,80,80,.1);border:1px solid rgba(255,80,80,.3);border-radius:10px;padding:11px 14px;margin-bottom:18px;font-size:.83rem;color:#ff6b6b;}
</style>
</head>
<body>
<div class="card">
  <h1>Command Center</h1>
  <p class="sub">Restricted access. Authorized personnel only.</p>
  @if($errors->any())
  <div class="error">{{ $errors->first() }}</div>
  @endif
  <form method="POST" action="/owner/login">
    @csrf
    <div class="field"><label>Email</label><input type="email" name="email" value="{{ old('email') }}" autocomplete="off" required/></div>
    <div class="field"><label>Password</label><input type="password" name="password" required/></div>
    <button type="submit" class="btn">Access Command Center</button>
  </form>
</div>
</body>
</html>
