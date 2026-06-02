<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Verifying — Keeption Vault</title>
<link rel="icon" type="image/png" href="/favicon.png"/>
<link href="https://fonts.googleapis.com/css2?family=Clash+Display:wght@400;500;600;700&family=Epilogue:wght@300;400;500&display=swap" rel="stylesheet"/>
<style>
:root{--bg:#04060a;--surface:#0c0f16;--border:rgba(255,255,255,0.07);--accent:#00f5a0;--text:#eef0f6;--muted:#636b7d;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
body{font-family:Epilogue,sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;}
body::before{content:'';position:fixed;top:-200px;left:50%;transform:translateX(-50%);width:700px;height:700px;background:radial-gradient(circle,rgba(0,245,160,0.05) 0%,transparent 70%);pointer-events:none;z-index:0;}
.wrap{position:relative;z-index:1;text-align:center;max-width:400px;}
.logo{font-family:'Clash Display',sans-serif;font-weight:700;font-size:1.4rem;margin-bottom:40px;}
.logo em{color:var(--accent);font-style:normal;}
.card{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:48px 40px;}
.spinner{width:48px;height:48px;border:3px solid rgba(0,245,160,0.15);border-top-color:var(--accent);border-radius:50%;animation:spin 0.8s linear infinite;margin:0 auto 28px;}
@keyframes spin{to{transform:rotate(360deg)}}
h1{font-family:'Clash Display',sans-serif;font-size:1.5rem;font-weight:700;letter-spacing:-0.5px;margin-bottom:10px;}
p{color:var(--muted);font-size:0.9rem;line-height:1.6;}
.error{display:none;color:#ff6b6b;font-size:0.88rem;margin-top:20px;}
</style>
</head>
<body>
<div class="wrap">
    <div class="logo">Keep<em>tion</em></div>
    <div class="card">
        <div class="spinner" id="spinner"></div>
        <h1>Verifying your email</h1>
        <p>Just a moment while we confirm your account...</p>
        <p class="error" id="error-msg"></p>
    </div>
</div>
<script>
(function () {
    // Supabase puts tokens in the URL fragment: #access_token=...&type=signup
    const hash = window.location.hash.substring(1);
    const params = new URLSearchParams(hash);
    const accessToken = params.get('access_token');
    const type = params.get('type');

    if (accessToken && (type === 'signup' || type === 'email')) {
        // POST token to Laravel so it can create the session server-side
        fetch('/auth/callback?access_token=' + encodeURIComponent(accessToken), {
            method: 'GET',
            redirect: 'follow',
        }).then(res => {
            window.location.href = '/dashboard';
        }).catch(() => showError());
    } else {
        // Maybe it came as a query param already (some Supabase configs)
        const urlParams = new URLSearchParams(window.location.search);
        const token = urlParams.get('access_token');
        if (token) {
            window.location.href = '/auth/callback?access_token=' + encodeURIComponent(token);
        } else {
            showError('No verification token found. The link may have expired.');
        }
    }

    function showError(msg) {
        document.getElementById('spinner').style.display = 'none';
        const el = document.getElementById('error-msg');
        el.style.display = 'block';
        el.textContent = msg || 'Verification failed. Please try registering again.';
    }
})();
</script>
</body>
</html>
