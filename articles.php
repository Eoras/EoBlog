<?php
include 'header.php';

// Vérification qu'il existe bien un ID
if (isset($_GET['article']) AND !empty($_GET['article'])) {
    $articleId = $_GET['article'];
} else {
    header("location: index.php");
}

// On récupère l'article sélectionné
$req = $bdd->prepare("SELECT *, DATE_FORMAT(date_creation,'%d/%m/%Y à %H:%i:%s') as date_creation_fr FROM article WHERE id = ?");
$req->execute([$articleId]);
$article = $req->fetch();
$req->closeCursor();
?>
    <nav class="mt-2 mb-3">
        <a class="btn btn-sm btn-dark float-left" href="index.php">Retour</a>
    </nav>
    <div class="clearfix"></div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card mb-2">
                <div class="card-header">
                    <span class="float-right">
                        <a href="articles.php?article=<?php echo $article['id']; ?>">Commentaires</a>
                        <span class="far fa-comments text-primary"></span>
                    </span>
                    <h3 class="m-0"><?php echo htmlspecialchars($article['titre']); ?></h3>
                </div>
                <div class="card-body">
                    <blockquote class="blockquote mb-0">
                        <p><?= nl2br(htmlspecialchars($article['contenu'])); ?></p>
                        <footer class="blockquote-footer">
                            <span class="far fa-calendar-alt"></span>
                            Créer par <?= $article['auteur']; ?> le <?= $article['date_creation_fr']; ?>
                        </footer>
                    </blockquote>
                </div>
                <div class="col-12">
                    <h3>Commentaires:</h3>
                    <?php include 'commentaires.php'; ?>
                </div>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>