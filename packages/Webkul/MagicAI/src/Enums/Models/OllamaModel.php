<?php

namespace Webkul\MagicAI\Enums\Models;

use Laravel\Ai\Enums\Lab;
use Webkul\MagicAI\Enums\Contracts\AiModelContract;

enum OllamaModel: string implements AiModelContract
{
    /**
     * Gemma family, with Gemma 4 the latest generation.
     */
    case Gemma4_26B = 'gemma4:26b';
    case Gemma4_12B = 'gemma4:12b';
    case Gemma3_27B = 'gemma3:27b';
    case Gemma3_12B = 'gemma3:12b';
    case Gemma3_4B = 'gemma3:4b';

    /**
     * Qwen family, with Qwen 3.8 the latest generation.
     */
    case Qwen38_27B = 'qwen3.8:27b';
    case Qwen3_30B = 'qwen3:30b';
    case Qwen3_8B = 'qwen3:8b';
    case Qwen25_7B = 'qwen2.5:7b';

    /**
     * GPT-OSS family, OpenAI's open-weight models.
     */
    case GptOss120B = 'gpt-oss:120b';
    case GptOss = 'gpt-oss:latest';

    /**
     * LLaMA family, with LLaMA 4 the latest generation.
     */
    case Llama4Maverick = 'llama4:maverick';
    case Llama4Scout = 'llama4:scout';
    case Llama33_70B = 'llama3.3:70b';
    case Llama31_8B = 'llama3.1:8b';
    case Llama32_3B = 'llama3.2:3b';
    case Llama32_1B = 'llama3.2:1b';

    /**
     * DeepSeek R1 reasoning family.
     */
    case DeepSeekR1_14B = 'deepseek-r1:14b';
    case DeepSeekR1_8B = 'deepseek-r1:8b';
    case DeepSeekR1_8B_Pinned = 'deepseek-r1:8b-0528-qwen3-q4_K_M';

    /**
     * Mistral family.
     */
    case MistralSmall32 = 'mistral-small3.2:24b';
    case Mistral_7B = 'mistral:7b';

    /**
     * Phi family.
     */
    case Phi4 = 'phi4';

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
            self::Gemma4_26B => 'Gemma 4 26B',
            self::Gemma4_12B => 'Gemma 4 12B',
            self::Gemma3_27B => 'Gemma 3 27B',
            self::Gemma3_12B => 'Gemma 3 12B',
            self::Gemma3_4B => 'Gemma 3 4B',
            self::Qwen38_27B => 'Qwen 3.8 27B',
            self::Qwen3_30B => 'Qwen 3 30B',
            self::Qwen3_8B => 'Qwen 3 8B',
            self::Qwen25_7B => 'Qwen 2.5 7B',
            self::GptOss120B => 'GPT-OSS 120B',
            self::GptOss => 'GPT-OSS 20B',
            self::Llama4Maverick => 'LLaMA 4 Maverick',
            self::Llama4Scout => 'LLaMA 4 Scout',
            self::Llama33_70B => 'LLaMA 3.3 70B',
            self::Llama31_8B => 'LLaMA 3.1 8B',
            self::Llama32_3B => 'LLaMA 3.2 3B',
            self::Llama32_1B => 'LLaMA 3.2 1B',
            self::DeepSeekR1_14B => 'DeepSeek R1 14B',
            self::DeepSeekR1_8B => 'DeepSeek R1 8B',
            self::DeepSeekR1_8B_Pinned => 'DeepSeek R1 8B Pinned',
            self::MistralSmall32 => 'Mistral Small 3.2 24B',
            self::Mistral_7B => 'Mistral 7B',
            self::Phi4 => 'Phi 4',
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
