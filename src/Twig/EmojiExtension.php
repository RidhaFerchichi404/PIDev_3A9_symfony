<?php

namespace App\Twig;

use App\Service\EmojiService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class EmojiExtension extends AbstractExtension
{
    public function __construct(
        private readonly EmojiService $emojiService
    ) {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('emojify', [$this, 'emojify'], ['is_safe' => ['html']]),
        ];
    }
    
    public function getFunctions(): array
    {
        return [
            new TwigFunction('has_emoji', [$this, 'hasEmoji']),
            new TwigFunction('count_emojis', [$this, 'countEmojis']),
        ];
    }

    public function emojify(string $text): string
    {
        return $this->emojiService->convertToEmoji($text);
    }
    
    public function hasEmoji(string $text): bool
    {
        return $this->emojiService->containsEmoji($text);
    }
    
    public function countEmojis(string $text): int
    {
        return $this->emojiService->countEmojis($text);
    }
} 