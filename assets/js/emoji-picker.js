console.log('Emoji picker script executing...');

class EmojiPicker {
    constructor() {
        console.log('Creating EmojiPicker instance...');
        this.emojiButtons = document.querySelectorAll('.emoji-button');
        // Try multiple selectors to find the textarea
        this.commentTextarea = document.querySelector('#form_comment') || 
                              document.querySelector('textarea[name="form[comment]"]') ||
                              document.querySelector('textarea.form-control');
        
        console.log('Found emoji buttons:', this.emojiButtons.length);
        console.log('Found textarea:', this.commentTextarea);
        
        if (this.emojiButtons.length && this.commentTextarea) {
            this.initialize();
        } else {
            console.error('Required elements not found:', {
                buttons: this.emojiButtons.length,
                textarea: !!this.commentTextarea
            });
        }
    }

    initialize() {
        console.log('Initializing emoji picker...');
        
        this.emojiButtons.forEach((button, index) => {
            console.log(`Setting up button ${index + 1}:`, button);
            const emoji = button.getAttribute('data-emoji');
            console.log(`Button ${index + 1} emoji value:`, emoji);
            
            button.addEventListener('click', (e) => {
                console.log('Button clicked:', button);
                e.preventDefault();
                this.insertEmoji(button);
            });
        });
    }

    insertEmoji(button) {
        const emoji = button.getAttribute('data-emoji');
        console.log('Attempting to insert emoji:', emoji);
        
        if (!emoji) {
            console.error('No emoji value found on button:', button);
            return;
        }

        const start = this.commentTextarea.selectionStart;
        const end = this.commentTextarea.selectionEnd;
        const text = this.commentTextarea.value;
        
        console.log('Current textarea state:', {
            start,
            end,
            textLength: text.length,
            currentValue: text
        });
        
        this.commentTextarea.value = text.substring(0, start) + emoji + text.substring(end);
        this.commentTextarea.focus();
        
        // Set cursor position after the inserted emoji
        const newPosition = start + emoji.length;
        this.commentTextarea.selectionStart = newPosition;
        this.commentTextarea.selectionEnd = newPosition;
        
        console.log('Emoji inserted successfully at position:', newPosition);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded, creating EmojiPicker...');
    new EmojiPicker();
}); 