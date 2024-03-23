<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

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
        $langs = to_list($request->header('accept-language'));

        $langs[] = self::DEFAULT_LOCALE;

        foreach ($langs as $lang) {
            if (in_array($lang, self::ALLOWED_LOCALES)) {
                app()->setLocale($lang);

                break;
            }
        }

        return $next($request);
    }
}
