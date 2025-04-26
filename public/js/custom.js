document.addEventListener('DOMContentLoaded', function() {
    // Style login/register links
    const authLinks = document.querySelector('.user-auth-links');
    if (authLinks) {
        const loginLink = authLinks.querySelector('a[href*="login"]');
        const registerLink = authLinks.querySelector('a[href*="register"]');
        
        if (loginLink) {
            loginLink.classList.add('login-btn');
            loginLink.style.marginRight = '10px';
        }
        
        if (registerLink) {
            registerLink.classList.add('register-btn');
        }
    }
    
    // Style profile/logout links
    const userLinks = document.querySelectorAll('.user-auth-links ul li');
    if (userLinks.length >= 2) {
        const container = document.createElement('div');
        container.className = 'user-profile-links';
        
        // Get parent ul
        const ul = userLinks[0].parentNode;
        
        // Create wrapper for the two links
        const profileLink = userLinks[0].querySelector('a');
        const logoutLink = userLinks[1].querySelector('a');
        
        if (profileLink && logoutLink) {
            // Remove existing list items
            userLinks[0].remove();
            userLinks[1].remove();
            
            // Add to container
            container.appendChild(profileLink);
            
            const divider = document.createElement('div');
            divider.className = 'divider';
            container.appendChild(divider);
            
            container.appendChild(logoutLink);
            
            // Create new li to hold the container
            const newLi = document.createElement('li');
            newLi.appendChild(container);
            
            // Add to ul
            ul.appendChild(newLi);
        }
    }
    
    // Add styling to menu links
    const navLinks = document.querySelectorAll('.nav-menu ul li a');
    navLinks.forEach(link => {
        link.style.fontWeight = '500';
    });
}); 