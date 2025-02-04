<?php
session_start();
include 'db.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "❌ Vous devez être connecté pour effectuer un virement.";
    exit;
}

$user_id = $_SESSION['user_id']; // ID de l'utilisateur connecté

function effectuerVirement($conn, $user_id, $compteSource, $compteDest, $montant) {
    // Vérifier si le compte source appartient à l'utilisateur
    $sql = "SELECT id, solde FROM compte WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $compteSource, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $compteData = $result->fetch_assoc();

    if (!$compteData) {
        return "❌ Ce compte ne vous appartient pas.";
    }

    if ($compteData['solde'] < $montant) {
        return "❌ Solde insuffisant !";
    }

    // Début de la transaction SQL
    $conn->begin_transaction();

    try {
        // Débiter le compte source
        $sqlDebiter = "UPDATE compte SET solde = solde - ? WHERE id = ?";
        $stmt = $conn->prepare($sqlDebiter);
        $stmt->bind_param("di", $montant, $compteSource);
        $stmt->execute();

        // Créditer le compte destination
        $sqlCrediter = "UPDATE compte SET solde = solde + ? WHERE id = ?";
        $stmt = $conn->prepare($sqlCrediter);
        $stmt->bind_param("di", $montant, $compteDest);
        $stmt->execute();

        // Insérer l'historique du virement
        $sqlTransaction = "INSERT INTO transactions (compte_source_id, compte_destination_id, montant) 
                           VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sqlTransaction);
        $stmt->bind_param("iid", $compteSource, $compteDest, $montant);
        $stmt->execute();

        // Valider la transaction
        $conn->commit();
        return "✅ Virement de $montant DZD effectué avec succès !";
    } catch (Exception $e) {
        $conn->rollback();
        return "❌ Erreur lors du virement.";
    }
}

// Vérifier si c'est un appel en POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $compteSource = intval($_POST["compteSource"]);
    $compteDest = intval($_POST["compteDest"]);
    $montant = floatval($_POST["montant"]);

    if ($compteSource && $compteDest && $montant > 0) {
        echo effectuerVirement($conn, $user_id, $compteSource, $compteDest, $montant);
    } else {
        echo "❌ Données invalides !";
    }
}
?>

<?php include 'header.php'; ?>
<link rel="stylesheet" href="veriment.css">

<div class="main-content">
    <section>
        <h2>Virement bancaire</h2>
        <p>Effectuez un virement entre deux comptes en toute simplicité.</p>
        <form class="virement-form" onsubmit="return false;">
            <label for="compteSource">Compte source :</label>
            <input type="text" id="compteSource" placeholder="Numéro de compte source" required>
            
            <label for="compteDestination">Compte destinataire :</label>
            <input type="text" id="compteDestination" placeholder="Numéro de compte destinataire" required>
            
            <label for="montantVirement">Montant à transférer (en DZD) :</label>
            <input type="number" id="montantVirement" placeholder="Montant" required>
            
            <button type="button" onclick="effectuerVirement()">Effectuer le virement</button>
        </form>
        <div id="virementResultat" class="result"></div>
    </section>
</div>

<?php include 'footer.php'; ?>
<script>
   function envoyerVirement() {
    let compteSource = document.getElementById("compteSource").value;
    let compteDestination = document.getElementById("compteDestination").value;
    let montant = document.getElementById("montantVirement").value;

    fetch("virement.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `compteSource=${compteSource}&compteDest=${compteDestination}&montant=${montant}`
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("virementResultat").innerHTML = data;
    });
}

</script>