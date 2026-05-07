<x-mail::message>
# 🎉 Your Connection Request Was Accepted!

Hi **{{ $sender->name }}**,

Wonderful news — **{{ $accepter->name }}** has accepted your connection request!

You are now connected and can view each other's full profile details. Take the next step and start a conversation.

<x-mail::button :url="$url" color="primary">
View {{ $accepter->name }}'s Profile
</x-mail::button>

We wish you both the very best on this journey. 💕

Warm regards,
**Engineer's Matrimony Team**
</x-mail::message>
