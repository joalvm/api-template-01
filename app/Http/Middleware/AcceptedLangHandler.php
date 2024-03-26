<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class AcceptedLangHandler
{
    public const LOCALE_ES = 'es';
    public const LOCALE_EN = 'en';

    public const ALLOWED_LOCALES = [
        self::LOCALE_ES,
        self::LOCALE_EN,
    ];

    public const DEFAULT_LOCALE = self::LOCALE_ES;

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response) $next
     */
    public function handle(Request $request, \Closure $next)
    {
        App::setLocale($this->getLanguage($request->getLanguages()));

        return $this->setHeader($next($request));
    }

    private function setHeader(Response $response)
    {
        $languages = implode(', ', self::ALLOWED_LOCALES);

        $response->headers->set('Content-Language', $languages);

        return $response;
    }

    private function getLanguage(array $languages): string
    {
        return Arr::first(
            $languages,
            fn ($value) => in_array($value, self::ALLOWED_LOCALES)
        ) ?? self::DEFAULT_LOCALE;
    }
}
