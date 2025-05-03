<?php

$logout = function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();

    return redirect('/');
};

?>

<button wire:click='logout'
    class="border border-custom-pink text-custom-pink rounded-full px-6 py-2 hover:bg-custom-pink hover:text-white">Logout</button>
