<?php
require "../auth.php";

$query = "SELECT utilisateur_id, pseudo, email, role_meta, statut FROM utilisateur WHERE role_meta != 'administrateur'";
$result = $PDO->query($query);

?>

<style>
.btn-suspend {
    background-color: red;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
}

.btn-reactivate {
    background-color: green;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
}

button:hover {
    opacity: 0.8;
}

</style>

<div class="container mt-5">
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="section-title">Gestion des Comptes</h5>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEmployeeModal">
                    Créer un Compte Employé
                </button>
                <input type="text" id="search-bar" class="form-control w-50" placeholder="Rechercher un utilisateur/employé...">
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pseudo</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="accounts-table">
                <?php while ($utilisateur = $result->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= htmlspecialchars($utilisateur['utilisateur_id']) ?></td>
                            <td><?= htmlspecialchars($utilisateur['pseudo']) ?></td>
                            <td><?= htmlspecialchars($utilisateur['email']) ?></td>
                            <td><?= htmlspecialchars($utilisateur['role_meta']) ?></td>
                            <td><?= htmlspecialchars($utilisateur['statut']) ?></td>
                            <td>
                            <?php
                                if ($utilisateur['statut'] === 'actif') {
                                    $buttonLabel = 'Suspendre';
                                    $buttonClass = 'btn-suspend';
                                    $buttonValue = 'suspendre';
                                } else {
                                    $buttonLabel = 'Réactiver';
                                    $buttonClass = 'btn-reactivate';
                                    $buttonValue = 'reactiver';
                                }
                                ?>
                                <form method="POST" action="process_statut.php">
                                    <input type="hidden" name="utilisateur_id" value="<?= htmlspecialchars($utilisateur['utilisateur_id']) ?>">
                                    <button type="submit" name="action" value="<?= $buttonValue ?>" class="btn <?= $buttonClass ?>">
                                        <?= $buttonLabel ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="section-title">Statistiques</h5>
            <div class="row">
                <div class="col-md-6">
                    <h6>Covoiturages par jour</h6>
                    <canvas id="ridesChart"></canvas>
                </div>
                <div class="col-md-6">
                    <h6>Crédit gagné par jour</h6>
                    <canvas id="earningsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Crédits -->
    <div class="card">
        <div class="card-body">
            <h5 class="section-title">Total des Crédits</h5>
            <p class="fs-4">
                <strong id="total-credits">189,000 crédits</strong>
                <small id="credits-date" class="text-muted"></small>
            </p>
        </div>
    </div>
</div>

<div class="modal fade" id="createEmployeeModal" tabindex="-1" aria-labelledby="createEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createEmployeeModalLabel">Créer un Compte Employé</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="create-employee-form" method="POST" action="process_create_employee.php">
                    <div class="mb-3">
                        <label for="employee-name" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="employee-name" name="employee_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="employee-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="employee-email" name="employee_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="employee-password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="employee-password" name="employee_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </form>
            </div>
        </div>
    </div>
</div>