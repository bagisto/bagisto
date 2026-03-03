<?php

namespace Webkul\MagicAI\Enums;

use Laravel\Ai\Enums\Lab;

enum AiModel: string
{
    /**
     * OpenAI — Text.
     */
    case GPT4O          = 'gpt-4o';
    case GPT4OMini      = 'gpt-4o-mini';
    case GPT4Turbo      = 'gpt-4-turbo';
    case GPT35Turbo     = 'gpt-3.5-turbo';
    case O1             = 'o1';
    case O1Mini         = 'o1-mini';
    case O3Mini         = 'o3-mini';

    /**
     * OpenAI — Images.
     */
    case DallE3 = 'dall-e-3';
    case DallE2 = 'dall-e-2';

    /**
     * Anthropic — Text.
     */
    case Claude35Sonnet = 'claude-3-5-sonnet-20241022';
    case Claude35Haiku  = 'claude-3-5-haiku-20241022';
    case ClaudeOpus4    = 'claude-opus-4-5';
    case ClaudeHaiku4   = 'claude-haiku-4-5';
    case Claude3Opus    = 'claude-3-opus-20240229';
    case Claude3Haiku   = 'claude-3-haiku-20240307';

    /**
     * Gemini — Text.
     */
    case Gemini20Flash     = 'gemini-2.0-flash';
    case Gemini20FlashLite = 'gemini-2.0-flash-lite';
    case Gemini20Pro       = 'gemini-2.0-pro-exp';
    case Gemini15Pro       = 'gemini-1.5-pro';
    case Gemini15Flash     = 'gemini-1.5-flash';

    /**
     * Gemini — Images.
     */
    case Imagen3 = 'imagen-3.0-generate-002';

    /**
     * Groq — Text.
     */
    case GroqLlama33_70B = 'llama-3.3-70b-versatile';
    case GroqLlama3_8B   = 'llama3-8b-8192';
    case GroqMixtral     = 'mixtral-8x7b-32768';
    case GroqGemma2      = 'gemma2-9b-it';

    /**
     * xAI — Text.
     */
    case Grok2Latest = 'grok-2-latest';
    case Grok2       = 'grok-2-1212';
    case GrokBeta    = 'grok-beta';

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
     */
    case MistralLarge = 'mistral-large-latest';
    case MistralSmall = 'mistral-small-latest';
    case Codestral    = 'codestral-latest';

    /**
     * Ollama — Text (common community models).
     */
    case OllamaLlama32_3B = 'llama3.2:3b';
    case OllamaLlama32_1B = 'llama3.2:1b';
    case OllamaLlama31_8B = 'llama3.1:8b';
    case OllamaLlama3_8B  = 'llama3:8b';
    case OllamaMistral_7B = 'mistral:7b';
    case OllamaDeepSeekR1 = 'deepseek-r1:8b';
    case OllamaQwen25_7B  = 'qwen2.5:7b';
    case OllamaQwen25_3B  = 'qwen2.5:3b';
    case OllamaPhi35      = 'phi3.5';
    case OllamaOrcaMini   = 'orca-mini';

    // -------------------------------------------------------------------------
    // Provider mapping
    // -------------------------------------------------------------------------

    public function provider(): Lab
    {
        return match ($this) {
            self::GPT4O, self::GPT4OMini, self::GPT4Turbo,
            self::GPT35Turbo, self::O1, self::O1Mini, self::O3Mini,
            self::DallE3, self::DallE2 => Lab::OpenAI,

            self::Claude35Sonnet, self::Claude35Haiku,
            self::ClaudeOpus4, self::ClaudeHaiku4,
            self::Claude3Opus, self::Claude3Haiku => Lab::Anthropic,

            self::Gemini20Flash, self::Gemini20FlashLite, self::Gemini20Pro,
            self::Gemini15Pro, self::Gemini15Flash,
            self::Imagen3 => Lab::Gemini,

            self::GroqLlama33_70B, self::GroqLlama3_8B,
            self::GroqMixtral, self::GroqGemma2 => Lab::Groq,

            self::Grok2Latest, self::Grok2, self::GrokBeta,
            self::Aurora => Lab::xAI,

            self::DeepSeekChat, self::DeepSeekReasoner => Lab::DeepSeek,

            self::MistralLarge, self::MistralSmall, self::Codestral => Lab::Mistral,

            self::OllamaLlama32_3B, self::OllamaLlama32_1B,
            self::OllamaLlama31_8B, self::OllamaLlama3_8B,
            self::OllamaMistral_7B, self::OllamaDeepSeekR1,
            self::OllamaQwen25_7B, self::OllamaQwen25_3B,
            self::OllamaPhi35, self::OllamaOrcaMini => Lab::Ollama,
        };
    }

    // -------------------------------------------------------------------------
    // Type helpers
    // -------------------------------------------------------------------------

    public function isImageModel(): bool
    {
        return $this->isAnyOf(
            self::DallE3,
            self::DallE2,
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

    // -------------------------------------------------------------------------
    // Labels
    // -------------------------------------------------------------------------

    public function label(): string
    {
        return match ($this) {
            self::GPT4O             => 'GPT-4o',
            self::GPT4OMini         => 'GPT-4o Mini',
            self::GPT4Turbo         => 'GPT-4 Turbo',
            self::GPT35Turbo        => 'GPT-3.5 Turbo',
            self::O1                => 'o1',
            self::O1Mini            => 'o1 Mini',
            self::O3Mini            => 'o3 Mini',
            self::DallE3            => 'DALL-E 3',
            self::DallE2            => 'DALL-E 2',
            self::Claude35Sonnet    => 'Claude 3.5 Sonnet',
            self::Claude35Haiku     => 'Claude 3.5 Haiku',
            self::ClaudeOpus4       => 'Claude Opus 4.5',
            self::ClaudeHaiku4      => 'Claude Haiku 4.5',
            self::Claude3Opus       => 'Claude 3 Opus',
            self::Claude3Haiku      => 'Claude 3 Haiku',
            self::Gemini20Flash     => 'Gemini 2.0 Flash',
            self::Gemini20FlashLite => 'Gemini 2.0 Flash Lite',
            self::Gemini20Pro       => 'Gemini 2.0 Pro',
            self::Gemini15Pro       => 'Gemini 1.5 Pro',
            self::Gemini15Flash     => 'Gemini 1.5 Flash',
            self::Imagen3           => 'Imagen 3',
            self::GroqLlama33_70B   => 'LLaMA 3.3 70B',
            self::GroqLlama3_8B     => 'LLaMA 3 8B',
            self::GroqMixtral       => 'Mixtral 8x7B',
            self::GroqGemma2        => 'Gemma2 9B',
            self::Grok2Latest       => 'Grok 2 Latest',
            self::Grok2             => 'Grok 2',
            self::GrokBeta          => 'Grok Beta',
            self::Aurora            => 'Aurora',
            self::DeepSeekChat      => 'DeepSeek Chat',
            self::DeepSeekReasoner  => 'DeepSeek Reasoner',
            self::MistralLarge      => 'Mistral Large',
            self::MistralSmall      => 'Mistral Small',
            self::Codestral         => 'Codestral',
            self::OllamaLlama32_3B  => 'LLaMA 3.2 3B',
            self::OllamaLlama32_1B  => 'LLaMA 3.2 1B',
            self::OllamaLlama31_8B  => 'LLaMA 3.1 8B',
            self::OllamaLlama3_8B   => 'LLaMA 3 8B',
            self::OllamaMistral_7B  => 'Mistral 7B',
            self::OllamaDeepSeekR1  => 'DeepSeek R1 8B',
            self::OllamaQwen25_7B   => 'Qwen 2.5 7B',
            self::OllamaQwen25_3B   => 'Qwen 2.5 3B',
            self::OllamaPhi35       => 'Phi 3.5',
            self::OllamaOrcaMini    => 'Orca Mini',
        };
    }
}
