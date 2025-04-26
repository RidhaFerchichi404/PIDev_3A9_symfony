/**
 * Initialisation des graphiques Chart.js
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('chart-init.js loaded');
    
    // Fonction pour créer un graphique en donut
    window.createDoughnutChart = function(elementId, labels, data, colors) {
        var element = document.getElementById(elementId);
        if (!element) {
            console.error('Element #' + elementId + ' not found');
            return null;
        }
        
        try {
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded');
                element.insertAdjacentHTML('afterend', '<div class="alert alert-danger">Erreur: Chart.js n\'est pas chargé</div>');
                return null;
            }
            
            var ctx = element.getContext('2d');
            return new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors || [
                            '#3498DB', // Blue
                            '#2ECC71', // Green
                            '#F1C40F', // Yellow
                            '#E74C3C', // Red
                            '#9B59B6', // Purple
                            '#1ABC9C', // Teal
                            '#F39C12'  // Orange
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 15,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creating chart:', error);
            element.insertAdjacentHTML('afterend', '<div class="alert alert-danger">Erreur: ' + error.message + '</div>');
            return null;
        }
    };
    
    // Fonction pour créer un graphique en camembert
    window.createPieChart = function(elementId, labels, data, colors) {
        var element = document.getElementById(elementId);
        if (!element) {
            console.error('Element #' + elementId + ' not found');
            return null;
        }
        
        try {
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded');
                element.insertAdjacentHTML('afterend', '<div class="alert alert-danger">Erreur: Chart.js n\'est pas chargé</div>');
                return null;
            }
            
            var ctx = element.getContext('2d');
            return new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors || [
                            '#E74C3C', // Red
                            '#3498DB', // Blue
                            '#2ECC71', // Green
                            '#F39C12'  // Orange
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creating chart:', error);
            element.insertAdjacentHTML('afterend', '<div class="alert alert-danger">Erreur: ' + error.message + '</div>');
            return null;
        }
    };
}); 