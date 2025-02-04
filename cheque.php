<?php include 'header.php'; ?>
<link rel="stylesheet" href="cheque.css">
<div class="main-content">
    <section>
        <h2>Chèque bancaire</h2>
        <p>Créez, gérez et consultez vos chèques bancaires en toute sécurité.</p>
        <form class="cheque-form">
            <input type="text" placeholder="Nom du bénéficiaire" required>
            <input type="text" placeholder="Montant en dinard algérien DZD" required>
            <input type="date" placeholder="Date" required>
            <input type="text" placeholder="Signature" required>
            <button type="submit">Valider le chèque</button>
        </form>
    </section>
</div>

<?php include 'footer.php'; ?>
