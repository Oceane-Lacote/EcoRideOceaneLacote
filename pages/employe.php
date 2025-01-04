<?php
require "../auth.php";

$query_avis = "SELECT avis_id, utilisateur_id_donneur AS passager, utilisateur_id_recepteur AS chauffeur, note, commentaire, statut FROM avis";
$result_avis = $PDO->query($query_avis);

$query_probleme = "SELECT id_probleme, id_covoiturage AS covoiturage, id_passager AS passager, id_conducteur AS chauffeur, commentaire FROM covoiturage_pb";
$result_probleme = $PDO->query($query_probleme);

$query_covoiturage = "SELECT id_covoiturage, date_depart, heure_depart, ville_depart, date_arrive, heure_arrive, ville_arrive FROM covoiturage";
$result_covoiturage = $PDO->query($query_covoiturage);
?>



<style>
    .header {
        background-color: #7f6751;
        color: white;
        padding: 20px;
        text-align: center;
    }

    .container {
        margin-top: 30px;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .btn-validate {
        background-color: #28a745;
        color: white;
    }

    .btn-refuse {
        background-color: #dc3545;
        color: white;
    }

    .section-title {
        margin-top: 40px;
        margin-bottom: 20px;
        font-size: 1.5rem;
    }

    .btn {
        border-radius: 5px;
    }

    .card {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 50px; /* Augmente l'espacement entre les sections */
    }

    .details-btn {
        background-color: #17a2b8;
        color: white;
    }
</style>
<div class="container">
    <div class="card">
        <div class="card-body">
            <h5 class="section-title">Gestion des Avis</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Passager</th>
                        <th>Chauffeur</th>
                        <th>Note</th>
                        <th>Commentaire</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($avis = $result_avis->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= htmlspecialchars($avis['passager']) ?></td>
                            <td><?= htmlspecialchars($avis['chauffeur']) ?></td>
                            <td><?= htmlspecialchars($avis['note']) ?></td>
                            <td><?= htmlspecialchars($avis['commentaire']) ?></td>
                            <td>
                            <form method="POST" action="pages/process_avis.php">
                                    <input type="hidden" name="avis_id" value="<?= htmlspecialchars($avis['avis_id']) ?>">
                                    <button type="submit" name="action" value="validate" class="btn btn-validate">Valider</button>
                                    <button type="submit" name="action" value="refuse" class="btn btn-refuse">Refuser</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


    <!-- Covoiturages Problématiques -->
    <div class="card">
        <div class="card-body">
            <h5 class="section-title">Covoiturages Problématiques</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Numéro Covoiturage</th>
                        <th>Passager</th>
                        <th>Chauffeur</th>
                        <th>Descriptif du Trajet</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($covoiturage_pb = $result_probleme->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= htmlspecialchars($covoiturage_pb['covoiturage']) ?></td>
                            <td><?= htmlspecialchars($covoiturage_pb['passager']) ?></td>
                            <td><?= htmlspecialchars($covoiturage_pb['chauffeur']) ?></td>
                            <td><?= htmlspecialchars(string: $covoiturage_pb['commentaire']) ?></td>
                    <?php endwhile; ?>
                            <td><button class="btn details-btn" data-bs-toggle="modal" data-bs-target="#problemDetailsModal">Vérifier</button></td>
                    </tr>        
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="problemDetailsModal" tabindex="-1" aria-labelledby="problemDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="problemDetailsModalLabel">Détails du Covoiturage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
    <?php while ($covoiturage = $result_covoiturage->fetch(PDO::FETCH_ASSOC)): ?>
        <p><strong>ID Covoiturage :</strong> <?= htmlspecialchars($covoiturage['id_covoiturage']) ?></p>
        <p><strong>Date de départ :</strong> <?= htmlspecialchars($covoiturage['date_depart']) ?></p>
        <p><strong>Heure de départ :</strong> <?= htmlspecialchars($covoiturage['heure_depart']) ?></p>
        <p><strong>Ville de départ :</strong> <?= htmlspecialchars($covoiturage['ville_depart']) ?></p>
        <p><strong>Date d'arrivée :</strong> <?= htmlspecialchars($covoiturage['date_arrive']) ?></p>
        <p><strong>Heure d'arrivée :</strong> <?= htmlspecialchars($covoiturage['heure_arrive']) ?></p>
        <p><strong>Ville d'arrivée :</strong> <?= htmlspecialchars($covoiturage['ville_arrive']) ?></p>
    <?php endwhile; ?>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fermer</button>
                </div>
                </div>
            </div>
