<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use Traits\Providers;

    public array $agent = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        'AppleWebKit/537.36 (KHTML, like Gecko)',
        'Chrome/86.0.4240.183',
        'Safari/537.36',
        'Edg/86.0.622.63',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:reset-tables', ['--yes' => true]);

        $this->withHeader('User-Agent', implode(' ', $this->agent));
    }
}
