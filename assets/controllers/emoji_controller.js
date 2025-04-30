import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ["textarea"];

  connect() {
    console.log("Emoji controller connected");
    console.log("Textarea target found:", this.hasTextareaTarget);
  }

  insert(event) {
    console.log("Insert emoji method called");
    
    if (!this.hasTextareaTarget) {
      console.error("No textarea target found");
      return;
    }
    
    const emoji = event.currentTarget.getAttribute('data-emoji-value');
    console.log("Emoji to insert:", emoji);
    
    const textarea = this.textareaTarget;
    const startPos = textarea.selectionStart || 0;
    const endPos = textarea.selectionEnd || 0;
    
    // Insert emoji at cursor position
    const newText = 
      textarea.value.substring(0, startPos) + 
      emoji + 
      textarea.value.substring(endPos);
    
    textarea.value = newText;
    
    // Update cursor position
    textarea.selectionStart = startPos + emoji.length;
    textarea.selectionEnd = startPos + emoji.length;
    
    // Focus the textarea
    textarea.focus();
  }
} 