<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

if (!function_exists('translate')) {
    function translate($key): string
    {
        $local = getDefaultLanguage();
        App::setLocale($local);

        // 🔥 THIS LINE WAS MISSING
        $key = strpos($key, 'messages.') === 0 ? substr($key, 9) : $key;
        try {
            $lang_array = include base_path("resources/lang/$local/messages.php");
            $processed_key = ucfirst(str_replace('_', ' ', removeSpecialCharacters($key)));
            $key = removeSpecialCharacters($key);

            if (!array_key_exists($key, $lang_array)) {
                $lang_array[$key] = $processed_key;
                file_put_contents(
                    base_path("resources/lang/$local/messages.php"),
                    "<?php return " . var_export($lang_array, true) . ";"
                );
                return $processed_key;
            }

            return __('messages.' . $key);
        } catch (\Exception $e) {
            return __('messages.' . $key);
        }
    }
}


if (!function_exists('removeSpecialCharacters')) {

    function removeSpecialCharacters(string $text): string
    {
        return str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', preg_replace('/\s\s+/', ' ', $text));
    }
}

if (!function_exists('getDefaultLanguage')) {
    function getDefaultLanguage(): string
    {
        if (strpos(url()->current(), '/api')) {
            $lang = App::getLocale();
        } elseif (session()->has('local')) {
            $lang = session('local');
        } else {
            $data = getWebConfig('language');
            $code = 'en';
            $direction = 'ltr';
            foreach ($data as $ln) {
                if (array_key_exists('default', $ln) && $ln['default']) {
                    $code = $ln['code'];
                    if (array_key_exists('direction', $ln)) {
                        $direction = $ln['direction'];
                    }
                }
            }
            session()->put('local', $code);
            Session::put('direction', $direction);
            $lang = $code;
        }
        return $lang;
    }
}
