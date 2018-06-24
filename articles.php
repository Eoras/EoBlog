<?php
$title = "";
include 'header.php';

// Verification that there is an ID
if (isset($_GET['article']) AND !empty($_GET['article'])) {
    $currentArticleId = $_GET['article'];
} else {
    header("location: /");
}

// We retrieve the selected article
$queryArticle = $dbConnect->prepare("SELECT *, DATE_FORMAT(date_created,'%d/%m/%Y à %H:%i:%s') as date_created_format FROM article WHERE id = ?");
$queryArticle->execute([$currentArticleId]);
$article = $queryArticle->fetch();
if (!$article) header('location: /');
$queryArticle->closeCursor();
?>
    <div class="row">
        <div class="col">
            <a class="btn btn-sm btn-dark float-right" href="index.php"><i class="fa fa-chevron-left" aria-hidden="true"></i> Go Back</a>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card mb-2">
                <div class="card-header p-2">
                    <p data-toggle="modal" data-target="#addCommentModal"
                       class="float-right btn btn-sm btn-light border-dark mb-0">
                        <i class="far fa-comments"></i>
                        Add a comment
                    </p>
                    <h4 class="m-0"><?php echo htmlspecialchars($article['title']); ?></h4>
                </div>
                <div class="card-body p-2">
                    <blockquote class="blockquote mb-0">
                        <p class="mb-0"><?= nl2br(htmlspecialchars($article['content'])); ?></p>
                        <footer class="blockquote-footer">
                            <small>
                                <span class="far fa-calendar-alt"></span>
                                Created by <?= $article['author']; ?> on <?= $article['date_created_format']; ?>
                            </small>
                        </footer>
                    </blockquote>
                </div>
                <div class="col-12">
                    <?php include 'comments.php'; ?>
                </div>
            </div>
        </div>
    </div>

    <!--MODAL ADD COMMENT-->
    <div class="modal fade" id="addCommentModal" tabindex="-1" role="dialog" aria-labelledby="addCommentModalTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCommentModalTitle">Add a comment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="js-errorsComment"></div>
                    <form id="addCommentForm">
                        <fieldset id="fs">
                            <input type="hidden" name="articleId" id="articleId" value="<?= $currentArticleId ?>">
                            <div class="form-group">
                                <label for="authorForm">Author</label>
                                <input type="text" name="author" id="authorForm" class="form-control"
                                       aria-describedby="author" placeholder="Your name">
                                <div class="feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="commentForm">Comment</label>
                                <textarea name="comment" id="commentForm" class="form-control" rows="5"
                                          placeholder="Your comment"></textarea>
                                <div class="feedback"></div>
                            </div>
                            <button type="submit" id="btnSubmitForm" class="btn btn-sm btn-dark float-right"
                                    disabled="disabled">Add
                            </button>
                            <div class="clearfix"></div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--JAVASCRIPT-->
    <script>
        let currentArticleId = <?= $currentArticleId ?>;
        let minLengthAuthor = <?= $pBlog_MinLengthAuthor ?>;
        let minLengthComment = <?= $pBlog_MinLengthComment ?>;
        let sendPost = false;
        const author = $('#authorForm');
        const comment = $('#commentForm');
        const formAddComment = $("#addCommentForm");
        const btnSubmit = $("#btnSubmitForm");
        let arrayElementValidFromForm = {'test': true, 'aa': true};

        author.on('input', function (e) {
            validator(author, minLengthAuthor, "Veuillez ajouter un prénom d'au minimum " + minLengthAuthor + " caractères.", "C'est parfait, salut " + author.val() + ".");
        });
        comment.on('input', function (e) {
            validator(comment, minLengthComment, "Veuillez ajouter du texte (au minimum " + minLengthComment + " caractères).", "C'est parfait.");
        });

        function validator(obj, nbCharacter, msgInvalid, msgValid) {
            if (obj.val() === '' || obj.val().length < nbCharacter) {
                obj.next('div.feedback').html(msgInvalid).addClass('invalid-feedback').removeClass('valid-feedback');
                obj.addClass('is-invalid text-danger').removeClass('is-valid text-success');
                btnSubmit.attr('disabled', 'disabled');
                arrayElementValidFromForm[obj.attr('name')] = false;
            } else {
                obj.next('div.feedback').html(msgValid).addClass('valid-feedback').removeClass('invalid-feedback');
                obj.addClass('is-valid text-success').removeClass('is-invalid text-danger');
                arrayElementValidFromForm[obj.attr('name')] = true;
            }

            if (arrayElementValidFromForm.author && arrayElementValidFromForm.comment) {
                btnSubmit.removeAttr('disabled', 'disabled');
                btnSubmit.removeClass('btn-dark').addClass('btn-success');
            } else {
                btnSubmit.attr('disabled', 'disabled');
                btnSubmit.removeClass('btn-success').addClass('btn-dark');
            }
        }

        formAddComment.submit(function (e) {
            $.ajax({
                type: "POST",
                url: "/addComment.php",
                data: formAddComment.serialize(),
                beforeSend: (e) => {
                    if (sendPost === false) {
                        $('#fs').attr('disabled', 'disabled');
                        btnSubmit.addClass('disabled').append(" <i class='fas fa-spinner fa-spin waiting'></i>");
                        console.log('Envoi en cours ...');
                        sendPost = true;
                    }
                },
                success: (data) => {
                    data = JSON.parse(data);
                    if (data.error) {
                        formAddComment.find('.is-valid').removeClass('is-valid');
                        formAddComment.find('.text-success').removeClass('text-success');
                        btnSubmit.removeClass('disabled').find('.waiting').remove();
                        $('#fs').removeAttr('disabled');
                        $('.js-errorsComment').html(`
                            <div class="row js-errorsComment">
                                <div class="col-12">
                                    <div class="alert alert-danger">
                                        ' + data.error + '
                                    </div>
                                </div>
                            </div>`);
                    } else if (data.success) {
                        $('.js-errorsComment').html('');
                        formAddComment.replaceWith(`
                            <div class="row validation">
                                <div class="col-12 text-center">
                                    <span class="fa fa-check text-success fa-10x" data-fa-transform="shrink-8 right-6"
                                        aria-hidden="true">
                                    </span>
                                    <p class="text-danger font-weight-bold">An admin will validate your comment, thanks</p>
                                </div>
                            </div>`);
                        setTimeout(() => {
                            location.replace('/articles.php?article=' + currentArticleId);
                        }, 5000);
                    } else {
                        sendPost = false;
                        btnSubmit.removeClass('disabled').find('.waiting').remove();
                        $('#fs').removeAttr('disabled');
                    }
                }
            });
            e.preventDefault();
        });
    </script>
<?php include 'footer.php'; ?>