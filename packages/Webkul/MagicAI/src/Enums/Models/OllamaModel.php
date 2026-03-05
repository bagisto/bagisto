<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum OllamaModel: string implements AiModelContract
{
    /**
     * LLaMA 4 family (latest generation — recommended for most use cases). All are suitable for chat and non-chat use cases.
     */
    case Llama4Scout = 'llama4:scout';
    case Llama4Maverick = 'llama4:maverick';

    /**
     * LLaMA 3 family (previous generation — still available). All are suitable for chat and non-chat use cases.
     *
     * Note: LLaMA 3.3 70B is the most capable model currently available on Ollama, and is recommended for most use cases.
     */
    case Llama33_70B = 'llama3.3:70b';
    case Llama32_3B = 'llama3.2:3b';
    case Llama32_1B = 'llama3.2:1b';
    case Llama31_8B = 'llama3.1:8b';

    /**
     * DeepSeek family (latest generation — recommended for most use cases). All are suitable for chat and non-chat use cases.
     */
    case DeepSeekR1_8B = 'deepseek-r1:8b';
    case DeepSeekR1_8B_Pinned = 'deepseek-r1:8b-0528-qwen3-q4_K_M';
    case DeepSeekR1_14B = 'deepseek-r1:14b';

    /**
     * Qwen family (latest generation — recommended for most use cases). All are suitable for chat and non-chat use cases.
     */
    case Qwen3_8B = 'qwen3:8b';
    case Qwen3_30B = 'qwen3:30b';
    case Qwen25_7B = 'qwen2.5:7b';

    /**
     * Phi family (latest generation — recommended for most use cases). All are suitable for chat and non-chat use cases.
     */
    case Phi4 = 'phi4';

    /**
     * Gemma family (latest generation — recommended for most use cases). All are suitable for chat and non-chat use cases.
     */
    case Gemma3_4B = 'gemma3:4b';
    case Gemma3_9B = 'gemma3:9b';
    case Gemma3_27B = 'gemma3:27b';

    /**
     * Mistral family (latest generation — recommended for most use cases). All are suitable for chat and non-chat use cases.
     */
    case Mistral_7B = 'mistral:7b';
    case MistralSmall3 = 'mistral-small3:latest';

    /**
     * GPT-OSS family (open-weight models, latest generation — recommended for open-weight use cases). All are suitable for chat and non-chat use cases.
     */
    case GptOss = 'gpt-oss:latest';

    /**
     * Get the SDK Lab provider this model belongs to.
     */
    public function provider(): Lab
    {
        return Lab::Ollama;
    }

    /**
     * Get the human-readable display name.
     */
    public function label(): string
    {
        return match ($this) {
            self::Llama4Scout => 'LLaMA 4 Scout',
            self::Llama4Maverick => 'LLaMA 4 Maverick',
            self::Llama33_70B => 'LLaMA 3.3 70B',
            self::Llama32_3B => 'LLaMA 3.2 3B',
            self::Llama32_1B => 'LLaMA 3.2 1B',
            self::Llama31_8B => 'LLaMA 3.1 8B',
            self::DeepSeekR1_8B => 'DeepSeek R1 8B',
            self::DeepSeekR1_8B_Pinned => 'DeepSeek R1 8B Pinned',
            self::DeepSeekR1_14B => 'DeepSeek R1 14B',
            self::Qwen3_8B => 'Qwen 3 8B',
            self::Qwen3_30B => 'Qwen 3 30B',
            self::Qwen25_7B => 'Qwen 2.5 7B',
            self::Phi4 => 'Phi 4',
            self::Gemma3_4B => 'Gemma 3 4B',
            self::Gemma3_9B => 'Gemma 3 9B',
            self::Gemma3_27B => 'Gemma 3 27B',
            self::Mistral_7B => 'Mistral 7B',
            self::MistralSmall3 => 'Mistral Small 3',
            self::GptOss => 'GPT-OSS',
        };
    }

    /**
     * Determine whether this model generates images.
     */
    public function isImageModel(): bool
    {
        return false;
    }

    /**
     * Determine whether this model generates text.
     */
    public function isTextModel(): bool
    {
        return true;
    }

    /**
     * Get the recommended default model for text generation.
     */
    public static function defaultTextModel(): ?static
    {
        return self::Llama32_3B;
    }

    /**
     * Get the recommended default model for image generation.
     */
    public static function defaultImageModel(): ?static
    {
        return null;
    }
}
