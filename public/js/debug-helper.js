/**
 * Script pour aider à la validation des formulaires et au débogage
 * Version améliorée avec messages d'erreur visuels
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Debug Helper loaded - Version avec messages d\'erreur visuels');
    
    // Ajouter du CSS pour les messages d'erreur
    addErrorStylesheet();
    
    // Fonction pour mettre en évidence les champs en erreur
    function highlightInvalidFields() {
        const form = document.querySelector('.salle-form');
        if (!form) return;
        
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            // Mettre en évidence les erreurs lors de la saisie
            input.addEventListener('input', function() {
                if (this.validity.valid) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    
                    // Effacer les messages d'erreur
                    const errorContainer = findErrorContainer(this);
                    if (errorContainer && errorContainer.innerHTML.trim() !== '') {
                        errorContainer.innerHTML = '';
                    }
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                    
                    // Afficher un message d'erreur personnalisé
                    const errorMessage = getValidationErrorMessage(this);
                    if (errorMessage) {
                        const errorContainer = findErrorContainer(this);
                        if (errorContainer) {
                            errorContainer.innerHTML = `<div class="custom-error-message">${errorMessage}</div>`;
                        }
                    }
                }
            });
            
            // Vérification initiale
            if (input.value) {
                if (input.validity.valid) {
                    input.classList.add('is-valid');
                } else {
                    input.classList.add('is-invalid');
                    
                    // Afficher un message d'erreur personnalisé
                    const errorMessage = getValidationErrorMessage(input);
                    if (errorMessage) {
                        const errorContainer = findErrorContainer(input);
                        if (errorContainer) {
                            errorContainer.innerHTML = `<div class="custom-error-message">${errorMessage}</div>`;
                        }
                    }
                }
            }
        });
        
        // Gérer la soumission du formulaire
        form.addEventListener('submit', function(event) {
            let hasErrors = false;
            
            inputs.forEach(input => {
                if (!input.validity.valid) {
                    input.classList.add('is-invalid');
                    
                    // Afficher un message d'erreur personnalisé
                    const errorMessage = getValidationErrorMessage(input);
                    if (errorMessage) {
                        const errorContainer = findErrorContainer(input);
                        if (errorContainer) {
                            errorContainer.innerHTML = `<div class="custom-error-message">${errorMessage}</div>`;
                        }
                    }
                    
                    hasErrors = true;
                }
            });
            
            if (hasErrors) {
                // Alerte avec style amélioré
                showCustomAlert('Veuillez corriger les erreurs dans le formulaire avant de soumettre.');
                event.preventDefault();
            }
        });
    }
    
    // Fonction pour trouver le conteneur d'erreur d'un champ
    function findErrorContainer(input) {
        // Chercher d'abord le conteneur d'erreur spécifique avec ID basé sur le nom du champ
        const fieldName = input.getAttribute('name');
        if (fieldName) {
            // Extraire le nom de base du champ (sans crochets pour les collections)
            const baseName = fieldName.replace(/\[([^\]]*)\]/g, '');
            const specificErrorContainer = document.getElementById(`${baseName}-error`);
            if (specificErrorContainer) {
                return specificErrorContainer;
            }
        }
        
        // Chercher le conteneur par ID basé sur l'ID de l'élément
        if (input.id) {
            const specificErrorContainer = document.getElementById(input.id + '-error');
            if (specificErrorContainer) {
                return specificErrorContainer;
            }
        }
        
        // Chercher un conteneur .error-message dans le parent du champ
        const formGroup = input.closest('.form-group');
        if (formGroup) {
            return formGroup.querySelector('.error-message');
        }
        
        return null;
    }
    
    // Fonction pour obtenir un message d'erreur personnalisé selon le type d'erreur
    function getValidationErrorMessage(input) {
        if (input.validity.valueMissing) {
            return 'Ce champ est obligatoire';
        } else if (input.validity.typeMismatch) {
            if (input.type === 'email') {
                return 'Veuillez entrer une adresse e-mail valide';
            } else if (input.type === 'url') {
                return 'Veuillez entrer une URL valide';
            } else {
                return 'Format invalide';
            }
        } else if (input.validity.patternMismatch) {
            if (input.name.includes('image')) {
                return 'Le fichier doit être une image (jpg, jpeg, png, gif ou webp)';
            } else if (input.name.includes('nom')) {
                return 'Le nom ne peut contenir que des lettres, chiffres, espaces, tirets et apostrophes';
            } else {
                return 'Format invalide: ' + (input.title || 'veuillez respecter le format requis');
            }
        } else if (input.validity.tooShort) {
            return `Minimum ${input.minLength} caractères requis`;
        } else if (input.validity.tooLong) {
            return `Maximum ${input.maxLength} caractères autorisés`;
        } else if (input.validity.rangeUnderflow) {
            return `La valeur doit être au moins ${input.min}`;
        } else if (input.validity.rangeOverflow) {
            return `La valeur ne peut pas dépasser ${input.max}`;
        } else if (input.validity.badInput) {
            return 'Veuillez entrer une valeur valide';
        }
        
        // Si on est ici mais que le champ est quand même invalide pour une autre raison
        if (!input.validity.valid) {
            return 'Ce champ contient une erreur';
        }
        
        return '';
    }
    
    // Validation personnalisée pour le nom de fichier image
    function setupImageFileValidation() {
        const imageInput = document.querySelector('[name$="[image]"]');
        if (!imageInput) return;
        
        imageInput.addEventListener('blur', function() {
            const value = this.value.trim();
            
            // Ignorer si vide (d'autres validateurs gèreront ça)
            if (!value) return;
            
            // Vérifier si le nom de fichier a une extension d'image valide
            if (!value.match(/\.(jpg|jpeg|png|gif|webp)$/i)) {
                this.classList.add('is-invalid');
                
                // Trouver le conteneur d'erreur
                const errorContainer = findErrorContainer(this);
                if (errorContainer) {
                    errorContainer.innerHTML = '<div class="custom-error-message">Le fichier doit être une image avec extension .jpg, .jpeg, .png, .gif ou .webp</div>';
                }
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                
                // Effacer les messages d'erreur
                const errorContainer = findErrorContainer(this);
                if (errorContainer) {
                    errorContainer.innerHTML = '';
                }
            }
        });
    }
    
    // Fonction pour afficher une alerte personnalisée
    function showCustomAlert(message) {
        const alertElement = document.createElement('div');
        alertElement.className = 'custom-alert';
        alertElement.innerHTML = `
            <div class="custom-alert-content">
                <i class="ri-error-warning-line"></i>
                <span>${message}</span>
                <button type="button" class="close-alert">&times;</button>
            </div>
        `;
        
        document.body.appendChild(alertElement);
        
        // Animation d'entrée
        setTimeout(() => alertElement.classList.add('show'), 10);
        
        // Fermer l'alerte en cliquant sur le bouton
        alertElement.querySelector('.close-alert').addEventListener('click', function() {
            alertElement.classList.remove('show');
            setTimeout(() => alertElement.remove(), 300);
        });
        
        // Fermer automatiquement après 5 secondes
        setTimeout(() => {
            if (document.body.contains(alertElement)) {
                alertElement.classList.remove('show');
                setTimeout(() => alertElement.remove(), 300);
            }
        }, 5000);
    }
    
    // Fonction pour ajouter des styles CSS pour les erreurs
    function addErrorStylesheet() {
        const style = document.createElement('style');
        style.textContent = `
            .is-invalid {
                border-color: #dc3545 !important;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right calc(0.375em + 0.1875rem) center;
                background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
                padding-right: calc(1.5em + 0.75rem) !important;
            }
            
            .is-valid {
                border-color: #198754 !important;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right calc(0.375em + 0.1875rem) center;
                background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
                padding-right: calc(1.5em + 0.75rem) !important;
            }
            
            .custom-error-message {
                color: #dc3545;
                font-size: 0.875rem;
                font-weight: bold;
                margin-top: 0.25rem;
                display: block;
            }
            
            .custom-alert {
                position: fixed;
                top: 20px;
                right: 20px;
                max-width: 350px;
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                border-radius: 4px;
                padding: 0;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                z-index: 9999;
                opacity: 0;
                transform: translateY(-20px);
                transition: opacity 0.3s, transform 0.3s;
            }
            
            .custom-alert.show {
                opacity: 1;
                transform: translateY(0);
            }
            
            .custom-alert-content {
                display: flex;
                align-items: center;
                padding: 12px 15px;
            }
            
            .custom-alert i {
                font-size: 20px;
                margin-right: 10px;
            }
            
            .close-alert {
                margin-left: auto;
                background: none;
                border: none;
                font-size: 20px;
                font-weight: bold;
                color: #721c24;
                cursor: pointer;
            }
        `;
        document.head.appendChild(style);
    }
    
    // Ajouter un gestionnaire spécial pour le champ zone
    function setupZoneFieldValidation() {
        const zoneSelect = document.querySelector('.zone-select');
        const zoneError = document.getElementById('zone-error');
        
        if (!zoneSelect || !zoneError) return;
        
        // Surveiller les changements
        zoneSelect.addEventListener('change', function() {
            if (this.value === '') {
                // Si aucune valeur n'est sélectionnée
                zoneError.innerHTML = '<div class="custom-error-message">Veuillez sélectionner une ville</div>';
                this.classList.add('is-invalid');
            } else {
                // Si une valeur valide est sélectionnée
                zoneError.innerHTML = '';
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    }
    
    // Appeler les fonctions
    highlightInvalidFields();
    setupImageFileValidation();
    setupZoneFieldValidation();
});
