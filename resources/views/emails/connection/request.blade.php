<x-mail::message>
# 💌 Someone is Interested in You!

Hi **{{ $receiver->name }}**,

Great news — **{{ $sender->name }}** has sent you a connection request on **Engineer's Matrimony**.

They liked your profile and would love to connect with you. Review their profile and decide if you'd like to accept.

<x-mail::button :url="$url" color="primary">
View Their Profile
</x-mail::button>

> This request will cost you **1 connection** to accept. Make sure you have enough balance.

If you're not interested, you can simply ignore this request.

Warm regards,
**Engineer's Matrimony Team**
</x-mail::message>
