/**
 * Form validation script
 * Shows error messages in English when input is invalid
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Form validation initialized');
    
    // Get form element
    const form = document.querySelector('.salle-form');
    if (!form) {
        console.error('Form not found');
        return;
    }
    
    // Get all input elements
    const inputs = form.querySelectorAll('input, select');
    
    // Error messages in English
    const errorMessages = {
        required: 'This field is required',
        minlength: 'This field must be at least {0} characters',
        maxlength: 'This field cannot exceed {0} characters',
        pattern: 'Please enter a valid format',
        region: 'Please select a region',
        zone: 'Please select a city',
        image: 'Please enter a valid image filename (jpg, jpeg, png, gif, webp)',
        user_id: 'Please enter a valid user ID (numbers only)'
    };
    
    // Function to show error message
    function showError(input, message) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        
        // Find error message container
        const errorContainer = input.closest('.form-group').querySelector('.error-message');
        if (errorContainer) {
            errorContainer.textContent = message;
            errorContainer.style.color = '#dc3545';
            errorContainer.style.display = 'block';
        }
    }
    
    // Function to clear error message
    function clearError(input) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        
        // Find error message container
        const errorContainer = input.closest('.form-group').querySelector('.error-message');
        if (errorContainer) {
            errorContainer.textContent = '';
            errorContainer.style.display = 'none';
        }
    }
    
    // Function to validate an input
    function validateInput(input) {
        // Check if input is required and empty
        if (input.hasAttribute('required') && !input.value.trim()) {
            showError(input, errorMessages.required);
            return false;
        }
        
        // Validate minlength
        if (input.hasAttribute('minlength') && input.value.length < parseInt(input.getAttribute('minlength'))) {
            const min = input.getAttribute('minlength');
            showError(input, errorMessages.minlength.replace('{0}', min));
            return false;
        }
        
        // Validate maxlength
        if (input.hasAttribute('maxlength') && input.value.length > parseInt(input.getAttribute('maxlength'))) {
            const max = input.getAttribute('maxlength');
            showError(input, errorMessages.maxlength.replace('{0}', max));
            return false;
        }
        
        // Validate pattern
        if (input.hasAttribute('pattern') && input.value && !new RegExp(input.getAttribute('pattern')).test(input.value)) {
            // Special message for image field
            if (input.id.includes('image')) {
                showError(input, errorMessages.image);
            } else {
                showError(input, errorMessages.pattern);
            }
            return false;
        }
        
        // Specific validation for region
        if (input.classList.contains('region-select') && !input.value) {
            showError(input, errorMessages.region);
            return false;
        }
        
        // Specific validation for zone
        if (input.classList.contains('zone-select') && !input.value) {
            showError(input, errorMessages.zone);
            return false;
        }
        
        // Specific validation for user ID (must be numeric)
        if (input.id.includes('id_user') && input.value && !/^\d+$/.test(input.value)) {
            showError(input, errorMessages.user_id);
            return false;
        }
        
        // If all validations pass
        clearError(input);
        return true;
    }
    
    // Add event listener to each input for real-time validation
    inputs.forEach(input => {
        ['blur', 'input', 'change'].forEach(eventType => {
            input.addEventListener(eventType, function() {
                validateInput(this);
            });
        });
    });
    
    // Special handling for zone select which depends on region
    const regionSelect = form.querySelector('.region-select');
    const zoneSelect = form.querySelector('.zone-select');
    
    if (regionSelect && zoneSelect) {
        regionSelect.addEventListener('change', function() {
            if (this.value) {
                // If region is selected, clear any error on zone
                // The region-zone-selector.js will handle populating the options
                clearError(zoneSelect);
            } else {
                showError(zoneSelect, errorMessages.zone);
            }
        });
    }
    
    // Validate form on submit
    form.addEventListener('submit', function(event) {
        let isValid = true;
        
        // Validate all inputs
        inputs.forEach(input => {
            if (!validateInput(input)) {
                isValid = false;
            }
        });
        
        // Prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault();
            event.stopPropagation();
            
            // Scroll to first error
            const firstInvalidInput = form.querySelector('.is-invalid');
            if (firstInvalidInput) {
                firstInvalidInput.focus();
                firstInvalidInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
}); 