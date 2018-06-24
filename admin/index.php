<?php
$title = "Articles";
include 'header.php';
?>
<?php
$query = $dbConnect->query('SELECT COUNT(*) as total from article');
$result = $query->fetch();
$totalArticle = $result['total'];
$query->closeCursor();
$totalNumberOfPages = ceil($totalArticle / ($pAdmin_NbArticlesPerPage | 1));

if ($totalArticle > 0 && $pAdmin_NbArticlesPerPage != 0) {
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $currentPage = intval($_GET['page']);
        if ($currentPage > $totalNumberOfPages) {
            header("location: ./index.php?page=$totalNumberOfPages");
        }
    } else {
        $currentPage = 1;
    }
    if ($totalArticle > 0) {
        $firstEntry = ($currentPage - 1) * $pAdmin_NbArticlesPerPage;
        $queryArticle = $dbConnect->query(
            "SELECT *, DATE_FORMAT(date_created, '%Y/%m/%d at %H:%i:%s') as date_created_format FROM article ORDER BY date_created DESC LIMIT $firstEntry, $pAdmin_NbArticlesPerPage");
    }
    ?>
    <div class="row">
        <div class="col-12">
            <nav aria-label="Page navigation">
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

    <div class="row">
        <?php
        while ($article = $queryArticle->fetch()): ?>
            <?php
            $queryComments = $dbConnect->prepare(
                'SELECT
                                (SELECT COUNT(*) FROM comment WHERE id_article = :id) as total,
                                (SELECT COUNT(*) FROM comment WHERE id_article = :id AND validated = true AND unvalidated=false) as totalValidated,
                                (SELECT COUNT(*) FROM comment WHERE id_article = :id AND validated = true AND unvalidated=true) as totalUnvalidated,
                                (SELECT COUNT(*) FROM comment WHERE id_article = :id AND validated = false) as totalNotValidated');
            $queryComments->bindParam(':id', $article['id']);
            $queryComments->execute();
            $comments = $queryComments->fetch();
            $totalComments = $comments['total'];
            $totalCommentValidated = $comments['totalValidated'];
            $totalCommentUnvalidated = $comments['totalUnvalidated'];
            $totalCommentNotValidated = $comments['totalNotValidated'];
            $queryComments->closeCursor();
            $visible = $article['visibility'];
            ?>
            <div class="col-12">
                <div class="card cardArticle mb-2 <?= $visible ? 'border-success' : 'border-warning' ?>">
                    <div class="card-header <?= $visible ? 'bg-success' : 'bg-warning' ?> text-white p-2">
                        <div class="float-right">
                            <a href="/admin/comments.php?article=<?= $article['id'] ?>"
                               class="btn btn-sm btn-light border-dark"
                               <?php if($totalComments) : ?>
                                   data-tooltips="tooltip"
                                   data-placement="top"
                                   title='<span class="text-left">
                                            <?= $totalCommentNotValidated ? '<p class="mb-0 text-danger font-weight-bold">To validate: ' . $totalCommentNotValidated . '</p>' : '' ?>
                                            <?= $totalCommentValidated ? '<p class="mb-0 text-success font-weight-bold">Visible: ' . $totalCommentValidated . '</p>' : '' ?>
                                            <?= $totalCommentUnvalidated ? '<p class="mb-0 text-warning font-weight-bold">Hidden: ' . $totalCommentUnvalidated . '</p>' : '' ?></span>'
                                   data-html="true"
                               <?php else :?>
                                   data-tooltips="tooltip"
                                   data-placement="top"
                                   title='No comment'
                               <?php endif; ?>
                            />
                                <i class="far fa-comments"></i>
                                <small class="d-inline-flex align-items-center">
                                    <span class="text-dark"><?= $totalComments ?? 0 ?></span>
                                    <?php if ($totalCommentNotValidated > 0) : ?>
                                             <img src="/assets/img/dot.png" class="pl-2" style="height: 8px">
                                    <?php endif; ?>
                                </small>
                            </a>
                            <form class="d-inline js-visibilityForm"
                                  data-id="<?= $article['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-light border-dark vsBtn"
                                        data-tooltips="tooltip"
                                        data-placement="top"
                                        title="<?= $visible ? "Make hidden" : "Make visible" ?>">
                                    <i class="far <?= $visible ? "fa-eye-slash" : "fa-eye" ?>"></i>
                                    <input type="hidden" name="id" value="<?= $article['id']; ?>">
                                </button>
                            </form>
                            <a href="/admin/article.php?edit=<?= $article['id']; ?>"
                               class="btn btn-sm btn-light border-dark" data-tooltips="tooltip"
                               data-placement="top" title="Edit this article">
                                <i class="far fa-edit"></i>
                            </a>
                            <button data-toggle="modal" data-target="#modalDeleteConfirmation"
                                    data-id="<?= $article['id'] ?>"
                                    data-title="<?= $article['title'] ?>"
                                    class="btn btn-sm btn-danger border-dark btnDelArticle" data-tooltips="tooltip"
                                    data-placement="top" title="Delete this article">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <h4 class="m-0"><?php echo htmlspecialchars($article['title']); ?></h4>
                    </div>
                    <div class="card-body p-2">
                        <blockquote class="blockquote mb-0">
                            <p class="mb-0">
                                <?= nl2br(htmlspecialchars($article['content'])); ?>
                            </p>
                            <footer class="blockquote-footer">
                                <small>
                                    <span class="far fa-calendar-alt"></span>
                                    <strong>Created by:</strong> <?= htmlspecialchars($article['author']); ?> on <?= $article['date_created_format']; ?>
                                </small>
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>
        <?php endwhile;
        $queryArticle->closeCursor(); ?>

    </div>

    <!-- Modal validation of the deletion of the article -->
    <div class="modal fade" id="modalDeleteConfirmation" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark text-light ">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Deleting the article</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="delArticle">
                    <div class="modal-body d-flex flex-wrap">
                        <div class="mx-4">
                            <span class="fa fa-exclamation-circle fa-3x text-danger" aria-hidden="true"></span>
                        </div>
                        <div class="">
                            <p class="mb-0">Are you sure you want to delete the article:</p>
                            <p class="font-weight-bold text-danger titleArticle mb-0"></p>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <input type="hidden" name="id" value="">
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-success">Agree</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script>
        const formDel = $('#delArticle');
        $('#modalDeleteConfirmation').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let id = button.data('id');
            let title = button.data('title');
            let modal = $(this);
            modal.find('.modal-footer input[name=id]').val(id);
            modal.find('.titleArticle').html(title);

        });

        formDel.submit(function (e) {
            $.ajax({
                type: "POST",
                url: "admin/action/delete.php",
                data: formDel.serialize(),
                success: (data) => {
                    location.reload();
                }
            });
            e.preventDefault();
        });

        // VISIBLE OR HIDDEN
        $(".js-visibilityForm").submit(function (e) {
            let btnSubmit = $(this).find('button');
            $.ajax({
                type: "POST",
                url: "/admin/action/visibility.php",
                data: $(this).serialize(),
                success: (data) => {
                    data = JSON.parse(data);
                    $('[data-tooltips="tooltip"]').tooltip('hide');
                    if (data.success === true && data.visibility === true) {
                        btnSubmit.attr('title', 'Make hidden');
                        btnSubmit.attr('data-original-title', 'Make hidden');
                        btnSubmit.closest('.card-header').removeClass('bg-warning').addClass('bg-success');
                        btnSubmit.closest('.card').removeClass('border-warning').addClass('border-success');
                        btnSubmit.find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                        btnSubmit.tooltip('show');
                    } else if (data.success === true && data.visibility === false) {
                        btnSubmit.attr('title', 'Make visible');
                        btnSubmit.attr('data-original-title', 'Make visible');
                        btnSubmit.closest('.card-header').removeClass('bg-success').addClass('bg-warning');
                        btnSubmit.closest('.card').removeClass('border-success').addClass('border-warning');
                        btnSubmit.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                        btnSubmit.tooltip('show');
                    }
                }
            });
            e.preventDefault();
        });
    </script>

    <?php
} else {
    echo "<p>No article</p>";
} ?>


<?php
include '../footer.php';
?>