<?php
// Inclure le fichier de configuration de la base de données
include_once 'scripts.php';

// Vérifier si un ID de script est passé dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $scriptId = $_GET['id'];

    // Récupérer les détails du script depuis la base de données
    $sql = "SELECT * FROM scripts WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $scriptId);
    $stmt->execute();
    $script = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si le script existe
    if (!$script) {
        echo "Script non trouvé.";
        exit();
    }
} else {
    echo "ID du script non spécifié.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter un script</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Consulter un script</h1>

        <!-- Détails du script -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($script['name']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($script['description']); ?></p>
                <?php if (!empty($script['file_name'])): ?>
                    <!-- Bouton de téléchargement -->
                    <a href="download-script.php?id=<?php echo $scriptId; ?>" class="btn btn-primary">Télécharger</a>
                <?php else: ?>
                    <!-- Bouton Copier le script -->
                    <button class="btn btn-primary" onclick="copyToClipboard()">Copier le script</button>
                    <pre><code><?php echo htmlspecialchars($script['content']); ?></code></pre>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bouton Retour vers la page principale -->
        <a href="index.php" class="btn btn-secondary mt-3">Retour</a>
    </div>

    <script src="bootstrap/js/bootstrap.js"></script>
    <script>
        function copyToClipboard() {
            var content = document.querySelector('pre').innerText;
            navigator.clipboard.writeText(content).then(function() {
                alert("Le contenu du script a été copié dans le presse-papiers !");
            }, function(err) {
                console.error('Erreur lors de la copie du texte : ', err);
            });
        }
    </script>
</body>
</html>
