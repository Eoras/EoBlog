<?php
// Récupérer le nombre total de commentaires:
$req = $bdd->prepare('SELECT COUNT(*) as totalCommentaire from commentaire WHERE id_article = ?');
$req->execute([$articleId]);
$totalCommentaire = $req->fetch()['totalCommentaire'];
$req->closeCursor();

$nombreDePages = ceil($totalCommentaire / ($configNombreCommentaireParPage | 1));

if ($totalCommentaire > 0 && $configNombreCommentaireParPage != 0) {
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $pageActuelle = intval($_GET['page']);
        if ($pageActuelle > $nombreDePages) {
            header("location: articles.php?article=$articleId&page=$nombreDePages");
        }
    } else {
        $pageActuelle = 1;
    }
    // Récupération de données en fonction de la page
    if ($totalCommentaire > 0) {
        $premiereEntree = ($pageActuelle - 1) * $configNombreCommentaireParPage;
        $req = $bdd->prepare("SELECT *, DATE_FORMAT(date_commentaire,'%d/%m/%Y') AS date_commentaire FROM commentaire WHERE id_article = :id ORDER BY date_commentaire DESC LIMIT $premiereEntree, $configNombreCommentaireParPage");
    }

    ?>

    <!--    PAGINATION-->
    <nav aria-label="Page navigation">
        <ul class="pagination pagination-sm justify-content-center mt-2">

            <?php
            if ($nombreDePages > 1 AND $pageActuelle != 1) : ?>
                <li class="page-item">
                    <a href="articles.php?article=<?= $articleId?>&page=<?= $pageActuelle - 1 ?>" aria-label="Next" class="page-link">
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
                    echo '<li class="page-item"><a class="page-link" href="articles.php?article=' . $articleId . '&page=' . $i . '">' . $i . '</a></li>';
                }
            }
            if ($nombreDePages > 1 AND $pageActuelle != $nombreDePages) : ?>
                <li class="page-item">
                    <a href="articles.php?article=<?= $articleId?>&page=<?= $pageActuelle + 1 ?>" aria-label="Next" class="page-link">
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
    $req->bindParam('id', $articleId);
    $req->execute();
    while ($commentaire = $req->fetch()) : ?>
        <div class="card bg-light border-dark p-2 mb-2">
            <blockquote class="blockquote mb-0">
                <p style="font-size: 0.6em" class="m-0"><?= nl2br(htmlspecialchars($commentaire['commentaire'])) ?></p>
                <footer class="blockquote-footer" style="font-size: 0.5em">
                    <span class="far fa-calendar-alt"></span>
                    Ajouté le <?= $commentaire['date_commentaire'] ?>
                    par <?= htmlspecialchars($commentaire['auteur']) ?>
                </footer>
            </blockquote>
        </div>
    <?php endwhile;
    $req->closeCursor();
} else {
    echo "<p>Aucun commentaire</p>";
}
?>