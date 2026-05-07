<x-mail::message>
# 📬 New Contact Form Message

You have received a new message via the website contact form.

<x-mail::table>
| Field | Details |
|:------|:--------|
| **Name** | {{ $name }} |
| **Email** | {{ $email }} |
@if($phone)
| **Phone** | {{ $phone }} |
@endif
| **Subject** | {{ $emailSubject }} |
</x-mail::table>

**Message:**

{{ $messageContent }}

---

You can reply directly to this email to respond to {{ $name }}.

**Engineer's Matrimony Team**
</x-mail::message>
