<x-mail::message>
    # Connection Request Accepted!

    Congratulations! **{{ $accepter->name }}** has accepted your connection request.

    You are now connected. You can view their full profile and start communicating.

    <x-mail::button :url="$url">
        View {{ $accepter->name }}'s Profile
    </x-mail::button>

    Best regards,<br>
    {{ config('app.name') }}
</x-mail::message>