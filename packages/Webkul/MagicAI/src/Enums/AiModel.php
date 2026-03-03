<?php

namespace Webkul\MagicAI\Enums;

use Laravel\Ai\Enums\Lab;

enum AiModel: string
{
    /**
     * OpenAI — Text.
     *
     * GPT-5 family (frontier).
     */
    case GPT52     = 'gpt-5.2';
    case GPT52Pro  = 'gpt-5.2-pro';
    case GPT5      = 'gpt-5';
    case GPT5Mini  = 'gpt-5-mini';
    case GPT5Nano  = 'gpt-5-nano';

    /**
     * OpenAI — Text.
     *
     * GPT-4 family.
     */
    case GPT41     = 'gpt-4.1';
    case GPT4O     = 'gpt-4o';
    case GPT4OMini = 'gpt-4o-mini';
    case GPT4Turbo = 'gpt-4-turbo';

    /**
     * OpenAI — Text.
     *
     * o-series reasoning models.
     */
    case O4Mini = 'o4-mini';
    case O3     = 'o3';
    case O3Mini = 'o3-mini';
    case O1     = 'o1';

    /**
     * OpenAI — Images.
     */
    case GptImage15 = 'gpt-image-1.5';
    case GptImage1  = 'gpt-image-1';
    case DallE3     = 'dall-e-3';

    /**
     * Anthropic — Text.
     *
     * Claude 4 family.
     */
    case ClaudeOpus46   = 'claude-opus-4-6';
    case ClaudeSonnet46 = 'claude-sonnet-4-6';
    case ClaudeHaiku45  = 'claude-haiku-4-5-20251001';

    /**
     * Anthropic — Text.
     *
     * Claude 3.5 family.
     */
    case Claude35Sonnet = 'claude-3-5-sonnet-20241022';
    case Claude35Haiku  = 'claude-3-5-haiku-20241022';

    /**
     * Anthropic — Text.
     *
     * Claude 3.
     */
    case Claude3Haiku = 'claude-3-haiku-20240307';

    /**
     * Gemini — Text.
     *
     * Gemini 2.5 (current stable).
     */
    case Gemini25Pro       = 'gemini-2.5-pro';
    case Gemini25Flash     = 'gemini-2.5-flash';
    case Gemini25FlashLite = 'gemini-2.5-flash-lite';

    /**
     * Gemini — Text.
     *
     * Gemini 2.0 (kept for compatibility).
     */
    case Gemini20Flash     = 'gemini-2.0-flash';
    case Gemini20FlashLite = 'gemini-2.0-flash-lite';

    /**
     * Gemini — Images.
     *
     * Imagen 4 family.
     */
    case Imagen4      = 'imagen-4.0-generate-001';
    case Imagen4Ultra = 'imagen-4.0-ultra-generate-001';
    case Imagen4Fast  = 'imagen-4.0-fast-generate-001';

    /**
     * Gemini — Images.
     *
     * Imagen 3.
     */
    case Imagen3 = 'imagen-3.0-generate-002';

    /**
     * Groq — Text.
     */
    case GroqLlama4Scout = 'meta-llama/llama-4-scout-17b-16e-instruct';
    case GroqLlama33_70B = 'llama-3.3-70b-versatile';
    case GroqLlama31_8B  = 'llama-3.1-8b-instant';

    /**
     * xAI — Text.
     */
    case Grok3       = 'grok-3';
    case Grok3Mini   = 'grok-3-mini';
    case Grok2Latest = 'grok-2-latest';
    case Grok2       = 'grok-2-1212';

    /**
     * xAI — Images.
     */
    case Aurora = 'aurora';

    /**
     * DeepSeek — Text.
     */
    case DeepSeekChat     = 'deepseek-chat';
    case DeepSeekReasoner = 'deepseek-reasoner';

    /**
     * Mistral — Text.
     *
     * Versioned (pinned) model IDs.
     */
    case MistralLarge3   = 'mistral-large-2512';
    case MistralMedium31 = 'mistral-medium-2508';
    case MistralSmall32  = 'mistral-small-2506';
    case MagistralMedium = 'magistral-medium-2509';
    case MagistralSmall  = 'magistral-small-2509';

    /**
     * Mistral — Text.
     *
     * Rolling aliases that always resolve to the latest stable release.
     */
    case MistralLarge = 'mistral-large-latest';
    case MistralSmall = 'mistral-small-latest';
    case Codestral    = 'codestral-latest';

    /**
     * Ollama — Text.
     *
     * Common community models available via a local Ollama server.
     */
    case OllamaLlama4Scout = 'llama4:scout';
    case OllamaLlama33_70B = 'llama3.3:70b';
    case OllamaLlama32_3B  = 'llama3.2:3b';
    case OllamaLlama32_1B  = 'llama3.2:1b';
    case OllamaLlama31_8B  = 'llama3.1:8b';
    case OllamaDeepSeekR1  = 'deepseek-r1:8b';
    case OllamaQwen3_8B    = 'qwen3:8b';
    case OllamaQwen25_7B   = 'qwen2.5:7b';
    case OllamaMistral_7B  = 'mistral:7b';
    case OllamaPhi4        = 'phi4';
    case OllamaGemma3_9B   = 'gemma3:9b';

    public function provider(): Lab
    {
        return match ($this) {
            self::GPT52, self::GPT52Pro,
            self::GPT5, self::GPT5Mini, self::GPT5Nano,
            self::GPT41, self::GPT4O, self::GPT4OMini, self::GPT4Turbo,
            self::O4Mini, self::O3, self::O3Mini, self::O1,
            self::GptImage15, self::GptImage1, self::DallE3 => Lab::OpenAI,

            self::ClaudeOpus46, self::ClaudeSonnet46, self::ClaudeHaiku45,
            self::Claude35Sonnet, self::Claude35Haiku,
            self::Claude3Haiku => Lab::Anthropic,

            self::Gemini25Pro, self::Gemini25Flash, self::Gemini25FlashLite,
            self::Gemini20Flash, self::Gemini20FlashLite,
            self::Imagen4, self::Imagen4Ultra, self::Imagen4Fast,
            self::Imagen3 => Lab::Gemini,

            self::GroqLlama4Scout, self::GroqLlama33_70B,
            self::GroqLlama31_8B => Lab::Groq,

            self::Grok3, self::Grok3Mini, self::Grok2Latest, self::Grok2,
            self::Aurora => Lab::xAI,

            self::DeepSeekChat, self::DeepSeekReasoner => Lab::DeepSeek,

            self::MistralLarge3, self::MistralMedium31, self::MistralSmall32,
            self::MagistralMedium, self::MagistralSmall,
            self::MistralLarge, self::MistralSmall, self::Codestral => Lab::Mistral,

            self::OllamaLlama4Scout, self::OllamaLlama33_70B,
            self::OllamaLlama32_3B, self::OllamaLlama32_1B, self::OllamaLlama31_8B,
            self::OllamaDeepSeekR1, self::OllamaQwen3_8B, self::OllamaQwen25_7B,
            self::OllamaMistral_7B, self::OllamaPhi4, self::OllamaGemma3_9B => Lab::Ollama,
        };
    }

    public function isImageModel(): bool
    {
        return $this->isAnyOf(
            self::GptImage15,
            self::GptImage1,
            self::DallE3,
            self::Imagen4,
            self::Imagen4Ultra,
            self::Imagen4Fast,
            self::Imagen3,
            self::Aurora,
        );
    }

    public function isTextModel(): bool
    {
        return ! $this->isImageModel();
    }

    /**
     * Whether this equals any of the given cases.
     */
    private function isAnyOf(self ...$others): bool
    {
        foreach ($others as $other) {
            if ($this === $other) {
                return true;
            }
        }

        return false;
    }

    public function label(): string
    {
        return match ($this) {
            // OpenAI — text
            self::GPT52             => 'GPT-5.2',
            self::GPT52Pro          => 'GPT-5.2 Pro',
            self::GPT5              => 'GPT-5',
            self::GPT5Mini          => 'GPT-5 Mini',
            self::GPT5Nano          => 'GPT-5 Nano',
            self::GPT41             => 'GPT-4.1',
            self::GPT4O             => 'GPT-4o',
            self::GPT4OMini         => 'GPT-4o Mini',
            self::GPT4Turbo         => 'GPT-4 Turbo',
            self::O4Mini            => 'o4 Mini',
            self::O3                => 'o3',
            self::O3Mini            => 'o3 Mini',
            self::O1                => 'o1',
            // OpenAI — images
            self::GptImage15        => 'GPT Image 1.5',
            self::GptImage1         => 'GPT Image 1',
            self::DallE3            => 'DALL-E 3',
            // Anthropic
            self::ClaudeOpus46      => 'Claude Opus 4.6',
            self::ClaudeSonnet46    => 'Claude Sonnet 4.6',
            self::ClaudeHaiku45     => 'Claude Haiku 4.5',
            self::Claude35Sonnet    => 'Claude 3.5 Sonnet',
            self::Claude35Haiku     => 'Claude 3.5 Haiku',
            self::Claude3Haiku      => 'Claude 3 Haiku',
            // Gemini — text
            self::Gemini25Pro       => 'Gemini 2.5 Pro',
            self::Gemini25Flash     => 'Gemini 2.5 Flash',
            self::Gemini25FlashLite => 'Gemini 2.5 Flash Lite',
            self::Gemini20Flash     => 'Gemini 2.0 Flash',
            self::Gemini20FlashLite => 'Gemini 2.0 Flash Lite',
            // Gemini — images
            self::Imagen4           => 'Imagen 4',
            self::Imagen4Ultra      => 'Imagen 4 Ultra',
            self::Imagen4Fast       => 'Imagen 4 Fast',
            self::Imagen3           => 'Imagen 3',
            // Groq
            self::GroqLlama4Scout   => 'LLaMA 4 Scout 17B',
            self::GroqLlama33_70B   => 'LLaMA 3.3 70B',
            self::GroqLlama31_8B    => 'LLaMA 3.1 8B',
            // xAI
            self::Grok3             => 'Grok 3',
            self::Grok3Mini         => 'Grok 3 Mini',
            self::Grok2Latest       => 'Grok 2 Latest',
            self::Grok2             => 'Grok 2',
            self::Aurora            => 'Aurora',
            // DeepSeek
            self::DeepSeekChat      => 'DeepSeek Chat',
            self::DeepSeekReasoner  => 'DeepSeek Reasoner',
            // Mistral — versioned
            self::MistralLarge3     => 'Mistral Large 3',
            self::MistralMedium31   => 'Mistral Medium 3.1',
            self::MistralSmall32    => 'Mistral Small 3.2',
            self::MagistralMedium   => 'Magistral Medium',
            self::MagistralSmall    => 'Magistral Small',
            // Mistral — aliases
            self::MistralLarge      => 'Mistral Large (Latest)',
            self::MistralSmall      => 'Mistral Small (Latest)',
            self::Codestral         => 'Codestral (Latest)',
            // Ollama
            self::OllamaLlama4Scout => 'LLaMA 4 Scout',
            self::OllamaLlama33_70B => 'LLaMA 3.3 70B',
            self::OllamaLlama32_3B  => 'LLaMA 3.2 3B',
            self::OllamaLlama32_1B  => 'LLaMA 3.2 1B',
            self::OllamaLlama31_8B  => 'LLaMA 3.1 8B',
            self::OllamaDeepSeekR1  => 'DeepSeek R1 8B',
            self::OllamaQwen3_8B    => 'Qwen 3 8B',
            self::OllamaQwen25_7B   => 'Qwen 2.5 7B',
            self::OllamaMistral_7B  => 'Mistral 7B',
            self::OllamaPhi4        => 'Phi 4',
            self::OllamaGemma3_9B   => 'Gemma 3 9B',
        };
    }
}
