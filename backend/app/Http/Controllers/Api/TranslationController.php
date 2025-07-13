<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

class TranslationController extends Controller
{
    /**
     * Get translations for a specific language
     */
    public function getTranslations(Request $request, $language = 'en'): JsonResponse
    {
        $supportedLanguages = ['en', 'fr', 'es'];
        
        if (!in_array($language, $supportedLanguages)) {
            $language = 'en';
        }
        
        $translationFile = base_path("lang/{$language}.json");
        
        if (!File::exists($translationFile)) {
            $translationFile = base_path("lang/en.json");
        }
        
        $translations = json_decode(File::get($translationFile), true);
        
        return response()->json([
            'success' => true,
            'language' => $language,
            'data' => $translations
        ]);
    }
    
    /**
     * Get available languages
     */
    public function getAvailableLanguages(): JsonResponse
    {
        $languages = [
            'en' => [
                'code' => 'en',
                'name' => 'English',
                'native' => 'English',
                'flag' => '🇺🇸'
            ],
            'fr' => [
                'code' => 'fr',
                'name' => 'French',
                'native' => 'Français',
                'flag' => '🇫🇷'
            ],
            'es' => [
                'code' => 'es',
                'name' => 'Spanish',
                'native' => 'Español',
                'flag' => '🇪🇸'
            ]
        ];
        
        return response()->json([
            'success' => true,
            'data' => $languages
        ]);
    }
} 