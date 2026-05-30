@extends('emails.layout')
@section('content')
<div class="body">
  <h2>✉️ Verify Your New Email Address</h2>
  <p>Hi {{ $user->name }},</p>
  <p>You recently requested to change the email address associated with your FOSA Task Management account.</p>
  <div class="meta">
    <p><strong>New email:</strong> {{ $newEmail }}</p>
    <p><strong>Account:</strong> {{ $user->username }}</p>
  </div>
  <p>Click the button below to confirm this change. Your email address will <strong>not</strong> be updated until you verify it.</p>
  <a href="{{ $link }}" class="btn">Verify New Email</a>
  <p style="margin-top:28px;font-size:13px;color:#6b7280;">
    If you didn't request this change, you can safely ignore this email — your current email address will remain unchanged.<br><br>
    This link is valid for single use only.
  </p>
</div>
@endsection
