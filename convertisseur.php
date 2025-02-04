<?php include 'header.php'; ?>
<link rel="stylesheet" href="convertisseur.css">
<section>
    <h2>Convertisseur de devises</h2>
    <p>Utilisez notre outil pour convertir instantanément des devises internationales.</p>
    <div class="convertisseur-form">
        <input type="number" id="montant" placeholder="Montant" required>
        <select id="source">
            <option value="">Devise source</option>
            <option value="DZD">DZD - Dinar Algérien</option>
            <option value="USD">USD - Dollar</option>
            <option value="EUR">EUR - Euro</option>
            <option value="GBP">GBP - Livre Sterling</option>
        </select>
        <select id="cible">
            <option value="">Devise cible</option>
            <option value="DZD">DZD - Dinar Algérien</option>
            <option value="USD">USD - Dollar</option>
            <option value="EUR">EUR - Euro</option>
            <option value="GBP">GBP - Livre Sterling</option>
        </select>
        <button onclick="convertirDevises()">Convertir</button>
        <div class="result" id="resultat"></div>
    </div>
</section>

<?php include 'footer.php'; ?>
<style>
    
html, body {
    overflow-x: hidden;
    width: 100%;
}

</style>
<script>
function convertirDevises() {
    const montant = document.getElementById('montant').value;
    const source = document.getElementById('source').value;
    const cible = document.getElementById('cible').value;

    if (!montant || !source || !cible) {
        alert("Veuillez remplir tous les champs.");
        return;
    }

    // Taux de change fictifs (à remplacer par des taux réels via une API)
    const tauxDeChange = {
        DZD: { USD: 0.0071, EUR: 0.0060, GBP: 0.0052, DZD: 1 },
        USD: { DZD: 140.5, EUR: 0.85, GBP: 0.73, USD: 1 },
        EUR: { DZD: 166.5, USD: 1.18, GBP: 0.86, EUR: 1 },
        GBP: { DZD: 193.5, USD: 1.37, EUR: 1.16, GBP: 1 }
    };

    const taux = tauxDeChange[source][cible];
    const resultat = montant * taux;

    document.getElementById('resultat').innerText = `${montant} ${source} = ${resultat.toFixed(2)} ${cible}`;
}
</script>