<!-- Modal Modifier Préférences -->
<div class="modal fade" id="editPreferencesModal" tabindex="-1" aria-labelledby="editPreferencesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPreferencesModalLabel">Modifier vos préférences</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPreferencesForm" action="update_preferences.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="preferences" class="form-label">Vos préférences :</label>
                        <textarea class="form-control" id="preferences" name="preferences" rows="4" required><?php echo htmlspecialchars($utilisateur['preferences']); ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Proposer un Trajet -->
<div class="modal fade" id="offerRideModal" tabindex="-1" aria-labelledby="offerRideModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="offerRideModalLabel">Proposer un trajet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="offerRideForm" action="offer_ride.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="departure" class="form-label">Lieu de départ :</label>
                        <input type="text" class="form-control" id="departure" name="departure" required>
                    </div>
                    <div class="mb-3">
                        <label for="arrival" class="form-label">Lieu d'arrivée :</label>
                        <input type="text" class="form-control" id="arrival" name="arrival" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date et heure :</label>
                        <input type="datetime-local" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Prix (€) :</label>
                        <input type="number" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="seats" class="form-label">Nombre de places disponibles :</label>
                        <input type="number" class="form-control" id="seats" name="seats" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Proposer</button>
                </div>
            </form>
        </div>
    </div>
</div>
