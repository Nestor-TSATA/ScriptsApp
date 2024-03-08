<?php
// Inclure le fichier de configuration de la base de données
include_once 'scripts.php';

// Vérifier si un script doit être supprimé
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $scriptId = $_GET['delete'];
    // Préparer la requête SQL de suppression du script
    $sql = "DELETE FROM scripts WHERE id = :id";
    // Exécuter la requête SQL
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(['id' => $scriptId])) {
        // Redirection vers la page index.php avec un message de succès
        header("Location: index.php?success=Le script a été supprimé avec succès");
        exit();
    } else {
        // Redirection vers la page index.php avec un message d'erreur
        header("Location: index.php?error=Une erreur s'est produite lors de la suppression du script");
        exit();
    }
}

// Vérifier si un script a été supprimé avec succès
if (isset($_GET['success'])) {
    $successMessage = htmlspecialchars($_GET['success']);
    // Afficher un message de succès avec JavaScript pour qu'il disparaisse après 5 secondes
    echo "<script>
            $(document).ready(function() {
                var successMessage = $('<div class=\"alert alert-success\" role=\"alert\"><?php echo $successMessage; ?></div>');
                $('#bod').prepend(successMessage);
                setTimeout(function() {
                    successMessage.fadeOut('slow');
                }, 5000);
            });
          </script>";
}

// Récupérer tous les scripts depuis la base de données
$sql = "SELECT id, name, description FROM scripts";
$stmt = $pdo->query($sql);
$scripts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des scripts</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <div class="container mt-4" id="bod">
        <h1 class="mb-3">Bienvenue dans votre gestionnaire des scripts</h1>

        <!-- Formulaire d'ajout/modification de script -->
        <div class="mb-4">
            <a href="script-form.php" class="btn btn-primary">Ajouter un script</a>
        </div>

        <!-- Afficher un message de succès s'il existe -->
        <?php if(isset($_GET['success'])): ?>
            <div id="successMessage" class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Affichage des scripts -->
        <div id="scripts-container">
            <h2>Liste des scripts</h2>
            <table id="scripts-table" class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Affichage des scripts avec les options de modification/suppression
                    foreach ($scripts as $script) {
                        echo "<tr>
                                <td>" . htmlspecialchars($script['name']) . "</td>
                                <td>" . htmlspecialchars($script['description']) . "</td>
                                <td>
                                    <a href='view-script.php?id=" . $script['id'] . "' class='btn btn-primary'>Consulter</a>
                                    <a href='script-form.php?id=" . $script['id'] . "' class='btn btn-info'>Modifier</a>
                                    <a href='?delete=" . $script['id'] . "' class='btn btn-danger' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce script ?\")'>Supprimer</a>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#scripts-table').DataTable();

            // Masquer le message de succès après 5 secondes
            setTimeout(function() {
                $('#successMessage').fadeOut('slow');
            }, 5000);
        });
    </script>
</body>
</html>
