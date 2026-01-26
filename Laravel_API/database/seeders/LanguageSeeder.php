<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'zh', 'name' => 'Chinese (Mandarin)'],
            ['code' => 'hi', 'name' => 'Hindi'],
            ['code' => 'es', 'name' => 'Spanish'],
            ['code' => 'fr', 'name' => 'French'],
            ['code' => 'ar', 'name' => 'Arabic'],
            ['code' => 'bn', 'name' => 'Bengali'],
            ['code' => 'ru', 'name' => 'Russian'],
            ['code' => 'pt', 'name' => 'Portuguese'],
            ['code' => 'ur', 'name' => 'Urdu'],
            ['code' => 'id', 'name' => 'Indonesian'],
            ['code' => 'de', 'name' => 'German'],
            ['code' => 'ja', 'name' => 'Japanese'],
            ['code' => 'sw', 'name' => 'Swahili'],
            ['code' => 'mr', 'name' => 'Marathi'],
            ['code' => 'te', 'name' => 'Telugu'],
            ['code' => 'tr', 'name' => 'Turkish'],
            ['code' => 'ta', 'name' => 'Tamil'],
            ['code' => 'ko', 'name' => 'Korean'],
            ['code' => 'vi', 'name' => 'Vietnamese'],
            ['code' => 'it', 'name' => 'Italian'],
            ['code' => 'th', 'name' => 'Thai'],
            ['code' => 'gu', 'name' => 'Gujarati'],
            ['code' => 'kn', 'name' => 'Kannada'],
            ['code' => 'fa', 'name' => 'Persian'],
            ['code' => 'pl', 'name' => 'Polish'],
            ['code' => 'uk', 'name' => 'Ukrainian'],
            ['code' => 'ro', 'name' => 'Romanian'],
            ['code' => 'nl', 'name' => 'Dutch'],
            ['code' => 'el', 'name' => 'Greek'],
            ['code' => 'hu', 'name' => 'Hungarian'],
            ['code' => 'sv', 'name' => 'Swedish'],
            ['code' => 'cs', 'name' => 'Czech'],
            ['code' => 'bg', 'name' => 'Bulgarian'],
            ['code' => 'da', 'name' => 'Danish'],
            ['code' => 'fi', 'name' => 'Finnish'],
            ['code' => 'sk', 'name' => 'Slovak'],
            ['code' => 'no', 'name' => 'Norwegian'],
            ['code' => 'he', 'name' => 'Hebrew'],
            ['code' => 'ms', 'name' => 'Malay'],
            ['code' => 'fil', 'name' => 'Filipino'],
            ['code' => 'hr', 'name' => 'Croatian'],
            ['code' => 'sr', 'name' => 'Serbian'],
            ['code' => 'sl', 'name' => 'Slovenian'],
            ['code' => 'lt', 'name' => 'Lithuanian'],
            ['code' => 'lv', 'name' => 'Latvian'],
            ['code' => 'et', 'name' => 'Estonian'],
            ['code' => 'hy', 'name' => 'Armenian'],
            ['code' => 'ka', 'name' => 'Georgian'],
            ['code' => 'az', 'name' => 'Azerbaijani'],
            ['code' => 'uz', 'name' => 'Uzbek'],
            ['code' => 'kk', 'name' => 'Kazakh'],
            ['code' => 'ky', 'name' => 'Kyrgyz'],
            ['code' => 'tg', 'name' => 'Tajik'],
            ['code' => 'tk', 'name' => 'Turkmen'],
            ['code' => 'mn', 'name' => 'Mongolian'],
            ['code' => 'ne', 'name' => 'Nepali'],
            ['code' => 'si', 'name' => 'Sinhala'],
            ['code' => 'km', 'name' => 'Khmer'],
            ['code' => 'lo', 'name' => 'Lao'],
            ['code' => 'my', 'name' => 'Burmese'],
            ['code' => 'am', 'name' => 'Amharic'],
            ['code' => 'so', 'name' => 'Somali'],
            ['code' => 'ha', 'name' => 'Hausa'],
            ['code' => 'yo', 'name' => 'Yoruba'],
            ['code' => 'ig', 'name' => 'Igbo'],
            ['code' => 'zu', 'name' => 'Zulu'],
            ['code' => 'xh', 'name' => 'Xhosa'],
            ['code' => 'af', 'name' => 'Afrikaans'],
            ['code' => 'st', 'name' => 'Sesotho'],
            ['code' => 'tn', 'name' => 'Tswana'],
            ['code' => 'sq', 'name' => 'Albanian'],
            ['code' => 'mk', 'name' => 'Macedonian'],
            ['code' => 'bs', 'name' => 'Bosnian'],
            ['code' => 'mt', 'name' => 'Maltese'],
            ['code' => 'is', 'name' => 'Icelandic'],
            ['code' => 'ga', 'name' => 'Irish'],
            ['code' => 'cy', 'name' => 'Welsh'],
            ['code' => 'gd', 'name' => 'Scottish Gaelic'],
            ['code' => 'eu', 'name' => 'Basque'],
            ['code' => 'ca', 'name' => 'Catalan'],
            ['code' => 'gl', 'name' => 'Galician'],
            ['code' => 'lb', 'name' => 'Luxembourgish'],
            ['code' => 'yi', 'name' => 'Yiddish'],
            ['code' => 'ps', 'name' => 'Pashto'],
            ['code' => 'ku', 'name' => 'Kurdish'],
            ['code' => 'sd', 'name' => 'Sindhi'],
            ['code' => 'pa', 'name' => 'Punjabi'],
            ['code' => 'ml', 'name' => 'Malayalam'],
            ['code' => 'or', 'name' => 'Odia'],
            ['code' => 'as', 'name' => 'Assamese'],
            ['code' => 'mai', 'name' => 'Maithili'],
            ['code' => 'bh', 'name' => 'Bhojpuri'],
            ['code' => 'dv', 'name' => 'Divehi'],
        ];

        foreach ($languages as $language) {
            Language::firstOrCreate(
                ['code' => $language['code']],
                $language
            );
        }
    }
}
