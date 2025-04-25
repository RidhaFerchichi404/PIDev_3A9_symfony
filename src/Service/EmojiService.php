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
        $emojiList = array_values($this->emojiMap);
        
        // Convert the text to an array of UTF-8 characters
        $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        $skipCount = 0;
        
        foreach ($chars as $index => $char) {
            if ($skipCount > 0) {
                $skipCount--;
                continue;
            }
            
            // Check if this character starts an emoji sequence
            foreach ($emojiList as $emoji) {
                $emojiLength = mb_strlen($emoji, 'UTF-8');
                
                // Try to match the current position with each emoji
                if ($index + $emojiLength <= count($chars)) {
                    $possibleEmoji = '';
                    for ($i = 0; $i < $emojiLength; $i++) {
                        if (isset($chars[$index + $i])) {
                            $possibleEmoji .= $chars[$index + $i];
                        }
                    }
                    
                    if ($possibleEmoji === $emoji) {
                        $count++;
                        $skipCount = $emojiLength - 1; // Skip the characters we already counted
                        break;
                    }
                }
            }
        }
        
        return $count;
    }
} 