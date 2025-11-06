<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    // You can leave it empty for now
    public function hosts(): array
    {
        return [];
    }
}

