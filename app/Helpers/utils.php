<?php

use Illuminate\Support\Facades\Lang;

if (!function_exists('singular_name')) {
    /**
     * Get the singular name of a resource.
     */
    function singular_name(string $key, bool $withArticle = false): string
    {
        if ($withArticle) {
            return Lang::get("api.$key.singular.full");
        }

        return Lang::get("api.$key.singular.name");
    }
}

if (!function_exists('plural_name')) {
    /**
     * Get the plural name of a resource.
     */
    function plural_name(string $key, bool $withArticle = false): string
    {
        if ($withArticle) {
            return Lang::get("api.$key.plural.full");
        }

        return Lang::get("api.$key.plural.name");
    }
}

if (!function_exists('resource_name')) {
    /**
     * Get the name of a resource.
     */
    function resource_name(string $key, int $count): string
    {
        if (0 === $count) {
            return plural_name($key);
        }

        if (1 === $count) {
            return singular_name($key);
        }

        return Lang::choice(plural_name($key), $count);
    }
}
