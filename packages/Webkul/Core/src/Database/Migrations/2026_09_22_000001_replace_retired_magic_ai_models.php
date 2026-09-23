<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The storefront feature settings that store the model a feature runs on.
     */
    const MODEL_CODES = [
        'magic_ai.storefront_features.image_search.model',
        'magic_ai.storefront_features.review_translation.model',
        'magic_ai.storefront_features.checkout_message.model',
    ];

    /**
     * Models a provider has retired or no longer offers, old model => the replacement its provider recommends.
     */
    const REPLACEMENTS = [
        'gpt-5.2' => 'gpt-5.6-sol',
        'gpt-5.1' => 'gpt-5.6-sol',
        'gpt-5' => 'gpt-5.6-sol',
        'gpt-5-mini' => 'gpt-5.6-terra',
        'gpt-5-nano' => 'gpt-5.6-luna',
        'gpt-4.1-nano' => 'gpt-5.6-luna',
        'claude-sonnet-4-20250514' => 'claude-sonnet-4-6',
        'gemini-2.5-pro' => 'gemini-3.8-flash',
        'gemini-2.5-flash' => 'gemini-3.8-flash',
        'gemini-2.5-flash-lite' => 'gemini-3.5-flash-lite',
        'llama-3.3-70b-versatile' => 'openai/gpt-oss-120b',
        'llama-3.1-8b-instant' => 'openai/gpt-oss-20b',
        'moonshotai/kimi-k2-instruct-0905' => 'openai/gpt-oss-120b',
        'qwen/qwen3-32b' => 'openai/gpt-oss-120b',
        'mistral-medium-2508' => 'mistral-medium-3-5',
        'mistral-small-2506' => 'mistral-small-2603',
        'magistral-medium-2509' => 'mistral-medium-3-5',
        'magistral-small-2509' => 'mistral-small-2603',
        'grok-4' => 'grok-4.3',
        'grok-4-1-fast' => 'grok-4.3',
        'grok-3' => 'grok-4.3',
        'grok-3-mini' => 'grok-4.3',
        'deepseek-chat' => 'deepseek-flash',
        'deepseek-reasoner' => 'deepseek-flash',
        'gemma3:9b' => 'gemma3:12b',
        'mistral-small3:latest' => 'mistral-small3.2:24b',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (self::REPLACEMENTS as $retired => $replacement) {
            DB::table('core_config')
                ->whereIn('code', self::MODEL_CODES)
                ->where('value', $retired)
                ->update(['value' => $replacement]);
        }
    }

    /**
     * Reverse the migrations, which leaves the replacements in place since the retired models no longer answer.
     */
    public function down(): void {}
};
