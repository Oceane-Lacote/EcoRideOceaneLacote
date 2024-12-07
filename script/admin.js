// Gestion des graphiques avec Chart.js
const ridesChartCtx = document.getElementById('ridesChart').getContext('2d');
const earningsChartCtx = document.getElementById('earningsChart').getContext('2d');

// Exemple de données pour les graphiques
const ridesData = {
    labels: ['01/12', '02/12', '03/12', '04/12', '05/12', '06/12'],
    datasets: [{
        label: 'Covoiturages',
        data: [12, 19, 3, 5, 2, 3],
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
    }]
};

const earningsData = {
    labels: ['01/12', '02/12', '03/12', '04/12', '05/12', '06/12'],
    datasets: [{
        label: 'Crédits gagnés',
        data: [50, 75, 100, 90, 60, 80],
        backgroundColor: 'rgba(153, 102, 255, 0.2)',
        borderColor: 'rgba(153, 102, 255, 1)',
        borderWidth: 1
    }]
};

// Initialisation des graphiques
new Chart(ridesChartCtx, {
    type: 'bar',
    data: ridesData,
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

new Chart(earningsChartCtx, {
    type: 'line',
    data: earningsData,
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Gestion des boutons "Suspendre" et "Réactiver"
document.getElementById('accounts-table').addEventListener('click', function (event) {
    if (event.target.classList.contains('suspend-btn')) {
        const row = event.target.closest('tr');
        row.querySelector('.badge').textContent = 'Suspendu';
        row.querySelector('.badge').classList.replace('bg-success', 'bg-danger');
        event.target.textContent = 'Réactiver';
        event.target.classList.replace('btn-danger', 'btn-success');
    } else if (event.target.classList.contains('reactivate-btn')) {
        const row = event.target.closest('tr');
        row.querySelector('.badge').textContent = 'Actif';
        row.querySelector('.badge').classList.replace('bg-danger', 'bg-success');
        event.target.textContent = 'Suspendre';
        event.target.classList.replace('btn-success', 'btn-danger');
    }
});

// Gestion du formulaire pour créer un compte employé
document.getElementById('create-employee-form').addEventListener('submit', function (event) {
    event.preventDefault();
    const name = document.getElementById('employee-name').value;
    const email = document.getElementById('employee-email').value;
    const password = document.getElementById('employee-password').value;

    const table = document.getElementById('accounts-table');
    const newRow = table.insertRow();
    newRow.innerHTML = `
        <td>${name}</td>
        <td>${email}</td>
        <td>Employé</td>
        <td><span class="badge bg-success">Actif</span></td>
        <td><button class="btn btn-danger suspend-btn">Suspendre</button></td>
    `;

    // Réinitialiser le formulaire
    document.getElementById('create-employee-form').reset();
    // Fermer la modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('createEmployeeModal'));
    modal.hide();
});
