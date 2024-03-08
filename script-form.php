<?php
// Inclure le fichier de configuration de la base de données
include_once 'scripts.php';

// Initialiser les variables pour stocker les valeurs du formulaire
$scriptDescription = '';
$successMessage = '';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si l'option "Téléverser un fichier" est sélectionnée
    if(isset($_FILES['scriptFile']) && $_FILES['scriptFile']['error'] === UPLOAD_ERR_OK) {
        // Lecture du contenu du fichier
        $fileContent = file_get_contents($_FILES['scriptFile']['tmp_name']);
        // Nom du fichier
        $fileName = $_FILES['scriptFile']['name'];

        // Vérifier si le champ description est rempli
        if(!empty($_POST['scriptDescription'])) {
            // Récupérer la description du formulaire
            $scriptDescription = $_POST['scriptDescription'];

            // Préparer la requête SQL d'insertion du nouveau script
            $sql = "INSERT INTO scripts (name, description, file_name, file_content) VALUES (:name, :description, :file_name, :file_content)";

            // On n'ajoute pas l'ID du script car c'est une insertion
            $data = [
                'name' => $fileName,
                'description' => $scriptDescription,
                'file_name' => $fileName,
                'file_content' => $fileContent
            ];

            // Exécuter la requête SQL
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute($data)) {
                // Afficher un message de confirmation
                $successMessage = "<div class='alert alert-success' role='alert'>Le fichier a été téléversé avec succès !</div>";
                // Effacer la valeur du champ description après l'ajout
                $scriptDescription = '';
            } else {
                // Afficher un message d'erreur en cas d'échec de l'ajout
                $successMessage = "<div class='alert alert-danger' role='alert'>Une erreur s'est produite lors du téléversement du fichier.</div>";
            }
        } else {
            // Afficher un message d'erreur si le champ description est vide
            $successMessage = "<div class='alert alert-danger' role='alert'>Veuillez fournir une description du script.</div>";
        }
    } else {
        // Afficher un message d'erreur si aucun fichier n'a été téléversé
        $successMessage = "<div class='alert alert-danger' role='alert'>Veuillez sélectionner un fichier à téléverser.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Téléverser un script</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Téléverser un script</h1>

        <!-- Afficher le message de confirmation/erreur -->
        <?php echo $successMessage; ?>

        <!-- Formulaire de téléversement de script -->
        <form action="script-form.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="scriptFile">Fichier du script</label>
                <input type="file" class="form-control-file" id="scriptFile" name="scriptFile" required>
            </div>
            <div class="form-group">
                <label for="scriptDescription">Description</label>
                <textarea class="form-control" id="scriptDescription" name="scriptDescription" rows="3" required><?php echo htmlspecialchars($scriptDescription); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Téléverser</button>
        </form>

        <!-- Bouton Retour vers la page principale -->
        <a href="index.php" class="btn btn-secondary mt-3">Retour</a>
    </div>

    <script src="bootstrap/js/bootstrap.js"></script>
</body>
</html>
