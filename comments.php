<?php
if(!isset($dbConnect)) header('location: /');

// Get the total number of comments for this article
$queryNumberOfComments = $dbConnect->prepare('SELECT COUNT(*) as total from comment WHERE id_article = ? AND validated = true AND unvalidated = false');
$queryNumberOfComments->execute([$currentArticleId]);
$totalComment = $queryNumberOfComments->fetch()['total'];
$queryNumberOfComments->closeCursor();

$totalNumberOfPages = ceil($totalComment / ($pBlog_NbCommentsPerPage | 1));

if ($totalComment > 0 && $pBlog_NbCommentsPerPage != 0) {
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $currentPage = intval($_GET['page']);
        if ($currentPage > $totalNumberOfPages) {
            header("location: articles.php?article=$currentArticleId&page=$totalNumberOfPages");
        }
    } else {
        $currentPage = 1;
    }

    ?>
    <!-- PAGINATION-->
    <nav aria-label="Page navigation" class="justify-content-between align-items-center d-flex mt-3">
        <h5>Comments:</h5>
        <ul class="pagination pagination-sm justify-content-right m-0">
            <?php
            if ($totalNumberOfPages > 1 AND $currentPage != 1) : ?>
                <li class="page-item">
                    <a href="articles.php?article=<?= $currentArticleId?>&page=<?= $currentPage - 1 ?>" aria-label="Preview" class="page-link py-0 px-1">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="disabled page-item">
                    <span aria-hidden="true" class="page-link py-0 px-1">&laquo;</span>
                </li>
            <?php endif;

            for ($i = 1; $i <= $totalNumberOfPages; $i++)
            {
                if ($i == $currentPage)
                {
                    echo '<li class="page-item active"><a href="#" class="page-link py-0">' . $i . '</a></li>';
                } else
                {
                    echo '<li class="page-item"><a class="page-link py-0" href="articles.php?article=' . $currentArticleId . '&page=' . $i . '">' . $i . '</a></li>';
                }
            }
            if ($totalNumberOfPages > 1 AND $currentPage != $totalNumberOfPages) : ?>
                <li class="page-item">
                    <a href="articles.php?article=<?= $currentArticleId?>&page=<?= $currentPage + 1 ?>" aria-label="Next" class="page-link py-0 px-1">
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

    <?php

    // Data retrieval by page
    if ($totalComment > 0) {
        $firstEntry = ($currentPage - 1) * $pBlog_NbCommentsPerPage;
        $queryComments = $dbConnect->prepare(
    "SELECT *, DATE_FORMAT(date_created,'%Y/%m/%d at %H:%i:%s')
              AS date_created_format
              FROM comment
              WHERE id_article = :id
                AND validated = true
                AND unvalidated = false
              ORDER BY date_created
              DESC LIMIT $firstEntry, $pBlog_NbCommentsPerPage");
        $queryComments->bindParam('id', $currentArticleId);
        $queryComments->execute();
        while ($comment = $queryComments->fetch()) : ?>
            <div class="card bg-light border-dark p-2 mb-2">
                <blockquote class="blockquote mb-0">
                    <p style="font-size: 0.6em" class="m-0"><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                    <footer class="blockquote-footer" style="font-size: 0.5em">
                        <span class="far fa-calendar-alt"></span>
                        <strong>Posted by:</strong> <?= htmlspecialchars($comment['author']) ?> on <?= $comment['date_created_format'] ?>
                    </footer>
                </blockquote>
            </div>
        <?php endwhile;
        $queryComments->closeCursor();
    }
} else {
    echo "<p>No comment</p>";
}
?>