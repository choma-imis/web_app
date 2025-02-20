<?php
namespace App\Helpers;
use App\Models\Language\Language;
use Illuminate\Support\Facades\Cookie;

    /*
    helper to compare a phrase with an array of keywords, and returns true if it matches any of the keywords
    */
    class LanguageSwitcher
    {
        public static function language_switcher()
        {

            $lang = 'en';

            if (Cookie::get('app_language') !== null) {
                $lang = Cookie::get('app_language');
            }
            $l = str_replace('_', '-', $lang);

            $text = '<li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown" aria-expanded="false">' . strtoupper($l) . '</a>
            <div class="dropdown-menu dropdown-menu-left" style="min-width: 50px; padding: 5px 0; font-size: 14px;">';
            $lang = Language::where('status', 'true')->get();

            foreach ($lang as $lng) {
                $text .= '<a class="dropdown-item" href="' . route('lang.switch') . '?lang=' . $lng->code . '" style="padding: 8px 15px; font-size: 13px;">' . strtoupper($lng->short) . '</a>';
            }

            $text .= '</div></li>';

            return $text ;
        }

}


