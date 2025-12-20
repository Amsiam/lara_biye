<x-mail::message>
    # New Connection Request

    Good news! **{{ $sender->name }}** has sent you a connection request.

    Someone is interested in your profile. Log in now to view their details and decide if you'd like to connect.

    <x-mail::button :url="$url">
        View Profile
    </x-mail::button>

    Best regards,<br>
    {{ config('app.name') }}
</x-mail::message>