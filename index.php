<?php
include 'header.php';
?>

<?php
// On récupère le total des articles:
$req = $bdd->query('SELECT COUNT(*) as totalArticle from article');
$result = $req->fetch();
$totalArticle = $result['totalArticle'];
$req->closeCursor();

//Nous allons maintenant compter le nombre de pages. (Attention à la division par 0)
$nombreDePages = ceil($totalArticle / ($configNombreArticleParPage | 1));

if ($totalArticle > 0 && $configNombreArticleParPage != 0) {
    // Vérification de la page en cours:
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $pageActuelle = intval($_GET['page']);
        if ($pageActuelle > $nombreDePages) {
            header("location: index.php?page=$nombreDePages");
        }
    } else {
        $pageActuelle = 1;
    }

    // Récupération de données en fonction de la page
    if ($totalArticle > 0) {
        $premiereEntree = ($pageActuelle - 1) * $configNombreArticleParPage;
        $req = $bdd->query("SELECT *, DATE_FORMAT(date_creation,'%d/%m/%Y à %H:%i:%s') as date_creation_fr FROM article ORDER BY date_creation_fr DESC LIMIT $premiereEntree, $configNombreArticleParPage");
    }
    ?>

    <nav aria-label="Page navigation">
        <ul class="pagination pagination-sm justify-content-center mt-2">

            <?php
            if ($nombreDePages > 1 AND $pageActuelle != 1) : ?>
                <li class="page-item">
                    <a href="?page=<?= $pageActuelle - 1 ?>" aria-label="Next" class="page-link">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="disabled page-item">
                    <span aria-hidden="true" class="page-link">&laquo;</span>
                </li>
            <?php endif;

            for ($i = 1; $i <= $nombreDePages; $i++) //On fait notre boucle
            {
                //On va faire notre condition
                if ($i == $pageActuelle) //Si il s'agit de la page actuelle...
                {
                    echo '<li class="page-item active"><a href="#" class="page-link">' . $i . '</a></li>';
                } else //Sinon...
                {
                    echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                }
            }
            if ($nombreDePages > 1 AND $pageActuelle != $nombreDePages) : ?>
                <li class="page-item">
                    <a href="?page=<?= $pageActuelle + 1 ?>" aria-label="Next" class="page-link">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <span aria-hidden="true" class="page-link">&raquo;</span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <?php
    echo '<div class="row">';

    while ($articles = $req->fetch()): ?>
        <div class="col-12">
            <div class="card mb-2">
                <div class="card-header">
                    <span class="float-right">
                        <a href="articles.php?article=<?php echo $articles['id']; ?>">Commentaires</a>
                        <span class="far fa-comments text-primary"></span>
                    </span>
                    <h3 class="m-0"><?php echo htmlspecialchars($articles['titre']); ?></h3>
                </div>
                <div class="card-body">
                    <blockquote class="blockquote mb-0">
                        <p><?= nl2br(htmlspecialchars($articles['contenu'])); ?></p>
                        <footer class="blockquote-footer">
                            <span class="far fa-calendar-alt"></span>
                            Créer par <?= $articles['auteur']; ?> le <?= $articles['date_creation_fr']; ?>
                        </footer>
                    </blockquote>
                </div>
            </div>
        </div>
    <?php endwhile;
    echo '</div>';
    $req->closeCursor();

} else {
    echo "<p>Aucun article</p>";
}
include 'footer.php';
?>