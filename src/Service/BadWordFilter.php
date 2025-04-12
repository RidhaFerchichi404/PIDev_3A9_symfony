<?php

namespace App\Service;

class BadWordFilter
{
    private array $badWords = [
        // English bad words
        'fuck', 'shit', 'asshole', 'bitch', 'bastard', 'damn', 'crap', 'dick', 'pussy', 'cock',
        'motherfucker', 'fucker', 'cunt', 'whore', 'slut', 'dickhead', 'wanker', 'twat', 'arsehole',
        'bullshit', 'fucking', 'shitty', 'piss', 'pissed', 'fag', 'faggot', 'nigger', 'nigga',
        'retard', 'retarded', 'idiot', 'moron', 'stupid', 'dumbass', 'douche', 'douchebag',
        
        // French bad words
        'merde', 'putain', 'con', 'connard', 'salope', 'enculé', 'nique', 'bite', 'couille',
        'chier', 'foutre', 'pute', 'pétasse', 'trouduc', 'branleur', 'niquer', 'enculer',
        'bordel', 'cul', 'chatte', 'conne', 'connasse', 'salaud', 'salopard', 'enfoiré',
        'fils de pute', 'ta mère', 'ta gueule', 'va te faire foutre', 'va te faire enculer',
        'va te faire voir', 'va te faire mettre', 'va te faire foutre', 'va te faire enculer',
        'va te faire voir', 'va te faire mettre'
    ];

    public function containsBadWords(string $text): bool
    {
        $text = ' ' . strtolower($text) . ' ';
        foreach ($this->badWords as $badWord) {
            $badWord = strtolower($badWord);
            // Check for exact word matches with word boundaries
            if (preg_match('/\b' . preg_quote($badWord, '/') . '\b/', $text)) {
                return true;
            }
        }
        return false;
    }

    public function filterBadWords(string $text): string
    {
        $words = explode(' ', $text);
        foreach ($words as &$word) {
            $lowerWord = strtolower($word);
            foreach ($this->badWords as $badWord) {
                if ($lowerWord === strtolower($badWord)) {
                    $word = str_repeat('*', strlen($word));
                    break;
                }
            }
        }
        return implode(' ', $words);
    }

    public function getBadWords(): array
    {
        return $this->badWords;
    }
} 