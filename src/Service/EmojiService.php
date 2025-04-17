<?php

namespace App\Service;

class EmojiService
{
    private array $emojiMap = [
        ':smile:' => '😊',
        ':laugh:' => '😂',
        ':wink:' => '😉',
        ':blush:' => '😊',
        ':heart:' => '❤️',
        ':heart_eyes:' => '😍',
        ':thumbsup:' => '👍',
        ':thumbsdown:' => '👎',
        ':clap:' => '👏',
        ':fire:' => '🔥',
        ':star:' => '⭐',
        ':eyes:' => '👀',
        ':thinking:' => '🤔',
        ':sad:' => '😢',
        ':cry:' => '😭',
        ':angry:' => '😠',
        ':rage:' => '😡',
        ':fearful:' => '😨',
        ':sunglasses:' => '😎',
        ':party:' => '🎉',
        ':ok:' => '👌',
        ':punch:' => '👊',
        ':muscle:' => '💪',
        ':pray:' => '🙏',
        ':confused:' => '😕',
        ':disappointed:' => '😞',
        ':worried:' => '😟',
        ':scream:' => '😱',
        ':joy:' => '😂',
        ':sweat_smile:' => '😅',
        ':lol:' => '🤣',
    ];

    /**
     * Converts shortcodes like :smile: to emoji characters in text
     */
    public function convertToEmoji(string $text): string
    {
        return str_replace(
            array_keys($this->emojiMap),
            array_values($this->emojiMap),
            $text
        );
    }

    /**
     * Gets all available emoji shortcodes
     */
    public function getAvailableEmojis(): array
    {
        return $this->emojiMap;
    }
    
    /**
     * Checks if a text contains any emoji characters
     */
    public function containsEmoji(string $text): bool
    {
        foreach (array_values($this->emojiMap) as $emoji) {
            if (strpos($text, $emoji) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Count the number of emojis in text
     */
    public function countEmojis(string $text): int
    {
        $count = 0;
        foreach (array_values($this->emojiMap) as $emoji) {
            $count += substr_count($text, $emoji);
        }
        
        return $count;
    }
} 