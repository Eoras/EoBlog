<?php
$title = "Comments";
include 'header.php';

if (isset($_GET['article']) && !empty($_GET['article'])) :

    // Verif if this article exist
    $query = $dbConnect->prepare("SELECT *, DATE_FORMAT(date_created,'%Y/%m/%d at %H:%i:%s') as date_created_format, DATE_FORMAT(date_updated, '%Y/%m/%d at %H:%i:%s') as date_updated_format from article WHERE id = :id");
    $query->bindParam(':id', $_GET['article'], \PDO::PARAM_INT);
    $query->execute();
    if (!$article = $query->fetch()) header('location: /admin/index.php');
    $articleId = $article['id'];
    $query->closeCursor();

    // Get comment totals
    $query = $dbConnect->prepare(
        'SELECT
                    (SELECT COUNT(*) FROM comment WHERE id_article = :id) as total,
                    (SELECT COUNT(*) FROM comment WHERE id_article = :id AND validated = true) as totalValidated,
                    (SELECT COUNT(*) FROM comment WHERE id_article = :id AND validated = false) as totalNotValidated');
    $query->bindParam(':id', $_GET['article'], \PDO::PARAM_INT);
    $query->execute();
    $comments = $query->fetch();
    $totalComments = $comments['total'];
    $totalCommentValidated = $comments['totalValidated'];
    $totalCommentNotValidated = $comments['totalNotValidated'];
    $query->closeCursor();

    $totalNumberOfPages = ceil($totalComments / ($pAdmin_NbCommentsPerPage | 1));

    if ($totalComments > 0 && $pAdmin_NbCommentsPerPage != 0) :
        if (isset($_GET['page']) && !empty($_GET['page'])) {
            $currentPage = intval($_GET['page']);
            if ($currentPage > $totalNumberOfPages) {
                header("location: ./comments.php?article=" . $articleId . "&page=$totalNumberOfPages");
            }
        } else {
            $currentPage = 1;
        }
        if ($totalComments > 0) {
            $firstEntry = ($currentPage - 1) * $pAdmin_NbCommentsPerPage;
            $queryComments = $dbConnect->query(
                "SELECT *, DATE_FORMAT(date_created, '%Y/%m/%d at %H:%i:%s') as date_created_format FROM comment WHERE id_article = $articleId ORDER BY validated ASC, date_created DESC LIMIT $firstEntry, $pAdmin_NbCommentsPerPage");
        }
        ?>

        <div class="row">
            <div class="col-12">
                <nav aria-label="Page navigation" class="justify-content-between align-items-center d-flex mt-3">
                    <a class="btn btn-sm btn-dark" href="index.php"><i class="fa fa-chevron-left"
                                                                       aria-hidden="true"></i> Go Back</a>
                    <ul class="pagination pagination-sm justify-content-end my-2">
                        <?php
                        if ($totalNumberOfPages > 1 AND $currentPage != 1) : ?>
                            <li class="page-item">
                                <a href="/admin/comments.php?article=<?= $articleId ?>&page=<?= $currentPage - 1 ?>"
                                   aria-label="Preview" class="page-link py-0 px-1">
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
                                echo '<li class="page-item"><a class="page-link py-0" href="/admin/comments.php?article=' . $articleId . '&page=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                        if ($totalNumberOfPages > 1 AND $currentPage != $totalNumberOfPages) : ?>
                            <li class="page-item">
                                <a href="/admin/comments.php?article=<?= $articleId ?>&page=<?= $currentPage + 1 ?>"
                                   aria-label="Next" class="page-link py-0 px-1">
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
    <?php else : ?>
        <div class="justify-content-between align-items-center d-flex mt-3 mb-1">
            <a class="btn btn-sm btn-dark" href="index.php"><i class="fa fa-chevron-left" aria-hidden="true"></i> Go
                Back</a>
            <p class="m-0 float-right">No comment</p>
        </div>
    <?php endif;
    $visible = $article['visibility'];
    ?>
    <div class="row">
        <div class="col-12">
            <div class="card <?= $visible ? "border-success" : "border-warning" ?>">
                <div class="card-header p-2 <?= $visible ? "bg-success" : "bg-warning" ?> ">
                    <h4 class="m-0"><?php echo htmlspecialchars($article['title']); ?></h4>
                </div>
                <div class="card-body p-2">
                    <blockquote class="blockquote mb-3">
                        <p class="mb-0"><?= nl2br(htmlspecialchars($article['content'])); ?></p>
                        <footer class="blockquote-footer">
                            <small>
                                <span class="far fa-calendar-alt"></span>
                                <strong>Created by:</strong> <?= $article['author']; ?>
                                on <?= $article['date_created_format'] .
                                ($article['date_updated_format'] ? " -- <strong>Updated: </strong>" . $article['date_updated_format'] : "") ?>
                            </small>
                        </footer>
                    </blockquote>
                    <?php
                    if ($totalComments > 0): ?>

                        <div class="legend">
                            <div class="d-inline bg-comments-notvalide border-dark border rounded p-1">
                                <img src="/assets/img/dot.png" style="height: 6px"> New comment to valide
                            </div>
                            <div class="d-inline bg-comments-unvalide border-dark border rounded p-1 ml-1">
                                Comment validated hidden
                            </div>
                            <div class="d-inline bg-comments-valide border-dark border rounded p-1 ml-1">
                                Comment validated visible
                            </div>
                        </div>

                        <?php while ($comment = $queryComments->fetch()) :
                            $validated = $comment['validated'];
                            $unvalidated = $comment['unvalidated'];
                            ?>
                            <div class="card js-comment-<?= $comment['id'] ?> <?= $validated ? ($unvalidated ? 'bg-comments-unvalide' : 'bg-comments-valide') : 'bg-comments-notvalide' ?> border-dark p-2 mt-2">
                                <blockquote class="blockquote mb-0">
                                    <div class="float-right">
                                        <?php
                                        if ($validated) : ?>
                                            <form class="d-inline js-manage-comment">
                                                <input type="hidden" name="action" value="unvalide">
                                                <input type="hidden" name="id" value="<?= $comment['id'] ?>">
                                                <button type="submit"
                                                        class="btn btn-sm border-dark p-1"
                                                        data-tooltips="tooltip"
                                                        data-placement="top"
                                                        title="<?= $unvalidated ? 'Make visible' : 'Make hidden' ?>">
                                                    <i class="fa fa-<?= $unvalidated ? 'eye' : 'eye-slash' ?> fa-fw"
                                                       aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        <?php
                                        else : ?>
                                            <img src="/assets/img/dot.png" class="new" data-tooltips="tooltip"
                                                 data-placement="top"
                                                 title="New">
                                            <form class="d-inline js-manage-comment">
                                                <input type="hidden" name="action" value="valide">
                                                <input type="hidden" name="id" value="<?= $comment['id'] ?>">
                                                <button type="submit"
                                                        class="btn btn-sm btn-success p-1"
                                                        data-tooltips="tooltip"
                                                        data-placement="top"
                                                        title="Valide this comment">
                                                    <i class="fa fa-check fa-fw" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        <?php
                                        endif;
                                        ?>
                                        <button class="btn btn-sm btn-danger border-dark p-1"
                                                data-tooltips="tooltip"
                                                data-placement="top"
                                                title="<?= $validated ? 'Delete' : 'Refuse' ?>"
                                                data-toggle="modal"
                                                data-target="#modalDeleteConfirmation"
                                                data-id="<?= $comment['id'] ?>">
                                            <i class="fas fa-fw fa-<?= $validated ? 'trash' : 'times' ?>"
                                               aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <p style="font-size: 0.6em"
                                       class="m-0"><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                                    <footer class="blockquote-footer" style="font-size: 0.5em">
                                        <span class="far fa-calendar-alt"></span>
                                        <strong>Posted by:</strong> <?= htmlspecialchars($comment['author']) ?>
                                        on <?= $comment['date_created_format'] ?>
                                    </footer>
                                    <div class="clearfix"></div>
                                </blockquote>
                            </div>
                        <?php
                        endwhile;
                        $queryComments->closeCursor();
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal validation of the deletion of the article -->
    <div class="modal fade" id="modalDeleteConfirmation" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark text-light ">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Deleting this comment</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="delComment">
                    <div class="modal-body d-flex flex-wrap">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="fa fa-exclamation-circle fa-3x text-danger mb-0 mx-3" aria-hidden="true"></p>
                            <div>
                                <p class="mb-0">Are you sure you want to delete this comment?</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="action" value="delete">
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-success">Agree</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>

        $('#modalDeleteConfirmation').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let id = button.data('id');
            let modal = $(this);
            modal.find('.modal-footer input[name=id]').val(id);
        });

        const formDelComment = $('#delComment');
        formDelComment.submit(function (e) {
            $.ajax({
                type: "POST",
                url: "/admin/action/comments.php",
                data: formDelComment.serialize(),
                success: (data) => {
                    data = JSON.parse(data);
                    if(data.success === true && data.action === 'delete') {
                        $('#modalDeleteConfirmation').modal('hide').find('.modal-footer input[name=id]').val('');
                        $('.js-comment-'+data.id).fadeOut("slow", "linear", () => {
                            $.when($('[data-tooltips="tooltip"]').tooltip('hide')).done(() => {
                                $('.js-comment-'+data.id).remove();
                            });
                            if(($(".card[class*='js-comment-']").length) <= 0) {
                                location.reload();
                            }
                        });
                    }
                }
            });
            e.preventDefault();
        });


        // VALIDE COMMENT
        $(".js-manage-comment").submit(function (e) {
            let btnSubmit = $(this).find('button');
            $.ajax({
                type: "POST",
                url: "/admin/action/comments.php",
                data: $(this).serialize(),
                success: (data) => {
                    data = JSON.parse(data);
                    console.log(btnSubmit.attr('data-original-title'));
                    $('[data-tooltips="tooltip"]').tooltip('hide');
                    if (btnSubmit.attr('data-original-title') === 'Make hidden') {
                        btnSubmit.attr('title', 'Make visible');
                        btnSubmit.attr('data-original-title', 'Make visible');
                        btnSubmit.closest('.card').removeClass('bg-comments-valide').addClass('bg-comments-unvalide');
                        btnSubmit.find('i').toggleClass('fa-eye-slash fa-eye');
                        btnSubmit.tooltip('show');
                    } else if (btnSubmit.attr('data-original-title') === 'Make visible'){
                        btnSubmit.attr('title', 'Make hidden');
                        btnSubmit.attr('data-original-title', 'Make hidden');
                        btnSubmit.closest('.card').removeClass('bg-comments-unvalide').addClass('bg-comments-valide');
                        btnSubmit.find('i').toggleClass('fa-eye-slash fa-eye');
                        btnSubmit.tooltip('show');
                    } else {
                        location.reload();
                    }
                }
            });
            e.preventDefault();
        });
    </script>

<?php
else:
    header('location: /admin/index.php');
endif;

include '../footer.php';
?>