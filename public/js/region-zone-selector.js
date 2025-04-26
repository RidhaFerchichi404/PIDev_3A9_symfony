/**
 * Dynamic region/zone selector script
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing region/zone selector');
    
    // DOM element selectors
    const regionSelect = document.querySelector('.region-select');
    const zoneSelect = document.querySelector('.zone-select');
    
    // If selectors don't exist on page, do nothing
    if (!regionSelect || !zoneSelect) {
        console.error('Region/zone selectors not found in DOM');
        return;
    }
    
    // Backup data for regions and zones
    const fallbackZones = {
        'Ariana': ['Ariana', 'Raoued', 'Ettadhamen', 'Kalâat el-Andalous', 'Sidi Thabet', 'Soukra', 'Mnihla'],
        'Béja': ['Béja', 'Medjez el-Bab', 'Téboursouk', 'Testour', 'Nefza', 'Amdoun'],
        'Ben Arous': ['Ben Arous', 'El Mourouj', 'Radès', 'Mégrine', 'Hammam Lif', 'Mornag'],
        'Bizerte': ['Bizerte', 'Menzel Bourguiba', 'Mateur', 'Ras Jebel', 'Sejnane', 'Tinja'],
        'Gabès': ['Gabès', 'El Hamma', 'Mareth', 'Matmata', 'Métouia', 'Menzel Habib'],
        'Gafsa': ['Gafsa', 'Métlaoui', 'El Ksar', 'Moularès', 'Redeyef', 'Mdhilla'],
        'Jendouba': ['Jendouba', 'Bou Salem', 'Tabarka', 'Aïn Draham', 'Ghardimaou', 'Fernana'],
        'Kairouan': ['Kairouan', 'Sbikha', 'Haffouz', 'Hajeb El Ayoun', 'Chebika', 'Oueslatia'],
        'Kasserine': ['Kasserine', 'Sbeitla', 'Fériana', 'Thala', 'Foussana', 'Haïdra'],
        'Kébili': ['Kébili', 'Douz', 'Souk Lahad', 'El Golâa', 'Jemna', 'Kébili Nord'],
        'Le Kef': ['Le Kef', 'Dahmani', 'Tajerouine', 'Sers', 'Kalâat Khasba', 'Nebeur'],
        'Mahdia': ['Mahdia', 'Ksour Essef', 'El Jem', 'Chebba', 'Souassi', 'Ouled Chamekh'],
        'Manouba': ['Manouba', 'Djedeida', 'Oued Ellil', 'Tebourba', 'El Battan', 'Mornaguia'],
        'Médenine': ['Médenine', 'Djerba Houmt Souk', 'Djerba Midoun', 'Djerba Ajim', 'Zarzis', 'Ben Gardane'],
        'Monastir': ['Monastir', 'Moknine', 'Jemmal', 'Ksar Hellal', 'Téboulba', 'Sayada'],
        'Nabeul': ['Nabeul', 'Hammamet', 'Kélibia', 'Korba', 'Menzel Temime', 'Grombalia'],
        'Sfax': ['Sfax', 'Sakiet Ezzit', 'Chihia', 'Sakiet Eddaïer', 'Mahres', 'Jebiniana'],
        'Sidi Bouzid': ['Sidi Bouzid', 'Regueb', 'Ouled Haffouz', 'Bir El Hafey', 'Sidi Ali Ben Aoun', 'Jilma'],
        'Siliana': ['Siliana', 'Bou Arada', 'Gaâfour', 'El Krib', 'Sidi Bou Rouis', 'Maktar'],
        'Sousse': ['Sousse', 'Msaken', 'Kalaâ Kebira', 'Akouda', 'Kalaâ Seghira', 'Hammam Sousse'],
        'Tataouine': ['Tataouine', 'Ghomrassen', 'Remada', 'Dehiba', 'Bir Lahmar', 'Smar'],
        'Tozeur': ['Tozeur', 'Degache', 'Nefta', 'Tamerza', 'Hezoua', 'Hammet Jerid'],
        'Tunis': ['Tunis', 'La Marsa', 'Le Bardo', 'Le Kram', 'Carthage', 'Sidi Bou Said'],
        'Zaghouan': ['Zaghouan', 'Zriba', 'Bir Mcherga', 'Djebel Oust', 'El Fahs', 'Nadhour'],
    };
    
    // Get pre-existing data if available
    const regionsData = getRegionData();
    
    // Function to get region data from data attributes
    function getRegionData() {
        // Try to get region data from data attribute
        try {
            const dataAttr = regionSelect.getAttribute('data-regions-data');
            if (dataAttr) {
                console.log('Region data found in data-attribute');
                return JSON.parse(dataAttr);
            }
        } catch (e) {
            console.error('Error retrieving region data:', e);
        }
        
        // If we get here, use backup data
        console.log('Using backup data for regions');
        return fallbackZones;
    }
    
    // Function to clear a select element
    function clearOptions(selectElement) {
        selectElement.innerHTML = '<option value="">Select a city</option>';
        console.log('Options cleared for', selectElement.className);
    }
    
    // Function to add options to a select element
    function addOptions(selectElement, options) {
        if (!options || options.length === 0) {
            console.error('No options to add');
            return;
        }
        
        console.log(`Adding ${options.length} options to ${selectElement.className}`);
        
        // Normalize options format based on their structure
        if (Array.isArray(options)) {
            if (typeof options[0] === 'string') {
                // Simple format: array of strings
                options.forEach(option => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option;
                    optionElement.textContent = option;
                    selectElement.appendChild(optionElement);
                });
                console.log('Options added (string array format)');
            } else if (typeof options[0] === 'object') {
                // Object format with id/name
                options.forEach(option => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option.id || option.nom;
                    optionElement.textContent = option.nom;
                    selectElement.appendChild(optionElement);
                });
                console.log('Options added (object array format)');
            }
        } else if (typeof options === 'object') {
            // Object format key/value
            Object.entries(options).forEach(([key, value]) => {
                const optionElement = document.createElement('option');
                optionElement.value = key;
                optionElement.textContent = value;
                selectElement.appendChild(optionElement);
            });
            console.log('Options added (key/value object format)');
        }
    }
    
    // Function to update zone options
    function updateZoneOptions() {
        // Clear current options
        clearOptions(zoneSelect);
        
        const regionValue = regionSelect.value;
        console.log('Updating zones for region:', regionValue);
        
        // If no region selected, do nothing more
        if (!regionValue) {
            console.warn('No region selected');
            return;
        }
        
        // First try to get the text of the selected option
        const selectedOption = regionSelect.options[regionSelect.selectedIndex];
        const regionName = selectedOption ? selectedOption.textContent : regionValue;
        console.log('Selected region name:', regionName);
        
        // Try to retrieve zones from API
        console.log(`Attempting to retrieve zones for ${regionName}`);
        
        // Try API using region name first
        fetch(`/api/regions/${encodeURIComponent(regionName)}/zones`)
            .then(response => {
                if (!response.ok) {
                    console.log(`API not available for ${regionName}, trying with ID ${regionValue}`);
                    // If it fails with name, try with ID
                    return fetch(`/api/regions/${encodeURIComponent(regionValue)}/zones`);
                }
                return response;
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Data received from API:', data);
                
                // Check if data is valid
                if (!data || (Array.isArray(data) && data.length === 0)) {
                    throw new Error('No city data found via API');
                }
                
                // Add retrieved zones
                addOptions(zoneSelect, data);
                
                // Trigger event to alert other scripts
                zoneSelect.dispatchEvent(new Event('change'));
            })
            .catch(error => {
                console.error('API error:', error);
                
                // Use backup data if available
                console.log('Attempting to use backup data');
                
                let zonesFound = false;
                
                // Try with exact name
                if (regionsData[regionName]) {
                    console.log(`Zones found for ${regionName} in backup data`);
                    addOptions(zoneSelect, regionsData[regionName]);
                    zonesFound = true;
                } 
                // Try with fallback
                else if (fallbackZones[regionName]) {
                    console.log(`Zones found for ${regionName} in static fallback`);
                    addOptions(zoneSelect, fallbackZones[regionName]);
                    zonesFound = true;
                }
                // Try case-insensitive search
                else {
                    console.log('Case-insensitive search in backup data');
                    const normalized = regionName.toLowerCase();
                    
                    // Search in region data
                    for (const [key, value] of Object.entries(regionsData)) {
                        if (key.toLowerCase() === normalized) {
                            console.log(`Match found for ${regionName} -> ${key}`);
                            addOptions(zoneSelect, value);
                            zonesFound = true;
                            break;
                        }
                    }
                    
                    // If still not found, search in fallback
                    if (!zonesFound) {
                        for (const [key, value] of Object.entries(fallbackZones)) {
                            if (key.toLowerCase() === normalized) {
                                console.log(`Match found in fallback for ${regionName} -> ${key}`);
                                addOptions(zoneSelect, value);
                                zonesFound = true;
                                break;
                            }
                        }
                    }
                }
                
                if (!zonesFound) {
                    console.error(`No cities found for region ${regionName}`);
                    
                    // Add empty option with message
                    const optionElement = document.createElement('option');
                    optionElement.value = "";
                    optionElement.textContent = `No cities available for ${regionName}`;
                    zoneSelect.appendChild(optionElement);
                } else {
                    // Trigger change event
                    zoneSelect.dispatchEvent(new Event('change'));
                }
            });
    }
    
    // Modify help text to reflect automatic functionality
    const helpText = document.querySelector('.zone-select').closest('.form-group').querySelector('.form-text');
    if (helpText) {
        helpText.innerHTML = 'Cities load automatically based on selected region';
    }
    
    // Remove refresh button if it exists
    const refreshButton = document.querySelector('.refresh-zones');
    if (refreshButton) {
        refreshButton.remove();
    }
    
    // Listen for changes on region selector
    regionSelect.addEventListener('change', function() {
        console.log('Region change detected:', this.value);
        updateZoneOptions();
    });
    
    // If a region is already selected on load, update zones
    if (regionSelect.value) {
        console.log('Region already selected on load:', regionSelect.value);
        updateZoneOptions();
        
        // Sometimes the value is set but the options aren't loaded yet
        // Add multiple attempts with increasing delays
        setTimeout(updateZoneOptions, 300);
        setTimeout(updateZoneOptions, 1000);
    } else {
        console.log('No region selected on load');
    }
}); 