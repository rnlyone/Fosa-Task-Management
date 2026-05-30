<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<style>
  body { margin:0; padding:0; background:#f0f4f8; font-family: 'Segoe UI', Arial, sans-serif; }
  .wrapper { max-width:600px; margin:32px auto; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,.08); }
  .header { background:#2563eb; padding:28px 32px; text-align:center; }
  .header img { height:48px; }
  .header h1 { color:#fff; margin:12px 0 0; font-size:20px; font-weight:600; }
  .body { padding:32px; color:#374151; line-height:1.7; }
  .body h2 { font-size:18px; color:#1e40af; margin-top:0; }
  .meta { background:#eff6ff; border-left:4px solid #2563eb; padding:14px 18px; border-radius:6px; margin:20px 0; font-size:14px; }
  .meta p { margin:4px 0; }
  .btn { display:inline-block; margin-top:24px; background:#2563eb; color:#fff !important; text-decoration:none; padding:12px 28px; border-radius:6px; font-weight:600; font-size:15px; }
  .btn-outline { display:inline-block; margin-top:24px; margin-left:12px; background:#fff; color:#2563eb !important; text-decoration:none; padding:11px 24px; border-radius:6px; font-weight:600; font-size:15px; border:2px solid #2563eb; }
  .footer { padding:20px 32px; background:#f8fafc; font-size:12px; color:#9ca3af; text-align:center; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <img src="{{ asset('fosalogo.png') }}" alt="FOSA" />
    <h1>FOSA Task Management</h1>
  </div>
  @yield('content')
  <div class="footer">
    This is an automated notification from FOSA Task Management System. Please do not reply to this email.
  </div>
</div>
</body>
</html>
