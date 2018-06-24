<?php
$title = "Articles";
include 'header.php';
?>

<?php
// We retrieve the total of the articles:
$query = $dbConnect->query('SELECT COUNT(*) as total from article WHERE article.visibility = true');
$result = $query->fetch();
$totalArticle = $result['total'];
$query->closeCursor();

// We will now count the number of pages. (Watch out for division by 0)
$totalNumberOfPages = ceil($totalArticle / ($pBlog_NbArticlesPerPage | 1));

if ($totalArticle > 0 && $pBlog_NbArticlesPerPage != 0) {
    // Checking the current page:
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $currentPage = intval($_GET['page']);
        if ($currentPage > $totalNumberOfPages) {
            header("location: index.php?page=$totalNumberOfPages");
        }
    } else {
        $currentPage = 1;
    }

    // Data retrieval by page:
    if ($totalArticle > 0) {
        $firstEntry = ($currentPage - 1) * $pBlog_NbArticlesPerPage;
        $queryArticles = $dbConnect->query("SELECT *, DATE_FORMAT(date_created,'%Y/%m/%d at %H:%i:%s') as date_created_format FROM article WHERE article.visibility = true ORDER BY date_created DESC LIMIT $firstEntry, $pBlog_NbArticlesPerPage");
    }
    ?>
    <div class="row">
        <div class="col-12">
            <nav aria-label="Navigation Page">
                <ul class="pagination pagination-sm justify-content-end my-2">
                    <?php
                    if ($totalNumberOfPages > 1 AND $currentPage != 1) : ?>
                        <li class="page-item">
                            <a href="?page=<?= $currentPage - 1 ?>" aria-label="Preview" class="page-link py-0 px-1">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="disabled page-item">
                            <span aria-hidden="true" class="page-link py-0 px-1">&laquo;</span>
                        </li>
                    <?php endif;

                    // Loop on pages
                    for ($i = 1; $i <= $totalNumberOfPages; $i++) {
                        if ($i == $currentPage) {
                            echo '<li class="page-item active"><a href="#" class="page-link py-0">' . $i . '</a></li>';
                        } else {
                            echo '<li class="page-item"><a class="page-link py-0" href="?page=' . $i . '">' . $i . '</a></li>';
                        }
                    }
                    if ($totalNumberOfPages > 1 AND $currentPage != $totalNumberOfPages) : ?>
                        <li class="page-item">
                            <a href="?page=<?= $currentPage + 1 ?>" aria-label="Next" class="page-link py-0 px-1">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled">
                            <span aria-hidden="true" class="page-link py-0 px-1">&raquo;</span>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
    <div class="row articleList">
        <?php
        while ($article = $queryArticles->fetch()): ?>
            <?php
            $queryComments = $dbConnect->prepare(
                'SELECT
                                (SELECT COUNT(*) FROM comment WHERE id_article = :id) as total,
                                (SELECT COUNT(*) FROM comment WHERE id_article = :id AND validated = true AND unvalidated = false) as totalValidated,
                                (SELECT COUNT(*) FROM comment WHERE id_article = :id AND validated = true AND unvalidated = false) as totalValidated,
                                (SELECT COUNT(*) FROM comment WHERE id_article = :id AND validated = false) as totalNotValidated');
            $queryComments->bindParam(':id', $article['id']);
            $queryComments->execute();
            $comments = $queryComments->fetch();
            $totalComments = $comments['total'];
            $totalCommentValidated = $comments['totalValidated'];
            $totalCommentNotValidated = $comments['totalNotValidated'];
            $queryComments->closeCursor();
            ?>
            <div class="col-12">
                <a class="articleLinkCard" href="articles.php?article=<?php echo $article['id']; ?>">
                    <div class="card js-shadow-hover mb-2">
                        <div class="card-header p-2">
                            <p class="float-right btn btn-sm btn-light border-dark mb-0">
                                <i class="far fa-comments"></i>
                                <small><?= $totalCommentValidated ?? 0 ?></small>
                            </p>
                            <h4 class="m-0"><?php echo htmlspecialchars($article['title']); ?></h4>
                        </div>
                        <div class="card-body p-2">
                            <blockquote class="blockquote mb-0">
                                <p class="mb-0"><?= nl2br(htmlspecialchars($article['content'])); ?></p>
                                <footer class="blockquote-footer">
                                    <small>
                                        <span class="far fa-calendar-alt"></span>
                                        <strong>Created by:</strong> <?= $article['author']; ?>
                                        on <?= $article['date_created_format']; ?>
                                    </small>
                                </footer>
                            </blockquote>
                        </div>
                    </div>
                </a>
            </div>
        <?php
        endwhile;
        $queryArticles->closeCursor();
        ?>
    </div>
    <?php
} else {
    echo "<p>No article</p>";
}
include 'footer.php';
?>