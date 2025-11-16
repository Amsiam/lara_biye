<x-mail::message>
# New Contact Message

You have received a new message from your website contact form.

**From:** {{ $name }}
**Email:** {{ $email }}
@if($phone)
**Phone:** {{ $phone }}
@endif
**Subject:** {{ $emailSubject }}

---

**Message:**

{{ $messageContent }}

---

You can reply directly to this email to respond to {{ $name }}.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
