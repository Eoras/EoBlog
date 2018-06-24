<?php
$edit = false;
if (isset($_GET['edit']) && !empty($_GET['edit'])) $edit = true;
$title = $edit === true ? "Edit" : "New";
include 'header.php';

if ($edit === true) {
    $query = $dbConnect->prepare("SELECT *, DATE_FORMAT(date_created,'%Y/%m/%d at %H:%i:%s') as date_created_format, DATE_FORMAT(date_updated, '%Y/%m/%d at %H:%i:%s') as date_updated_format from article WHERE id = :id");
    $query->bindParam(':id', $_GET['edit']);
    $query->execute();
    if (!$article = $query->fetch()) header('location: /admin/manageArticle.php');
    $query->closeCursor();
    $visible = $article['visibility'];
} else {
    $visible = true;
}
?>
    <div class="row mb-1 mt-3">
        <div class="col">
            <a class="btn btn-sm btn-dark" href="index.php"><i class="fa fa-chevron-left" aria-hidden="true"></i> Go Back</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <form method="POST" action="action/article.php">
                <div class="card mb-2 card border-<?= $visible ? 'success' : 'warning' ?> js-visibility-card">
                    <div class="card-header bg-<?= $visible ? 'success' : 'warning' ?> js-visibility-header text-white p-2">
                        <div class="input-group input-group-sm">
                            <input type="text" name="title" class="form-control form-control-sm"
                                   value="<?= htmlspecialchars($article['title']) ?? '' ?>"
                                   placeholder="Title" id="titleForm">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <input type="checkbox" class="form-check-input hidden" id="visibilityForm"
                                        <?= $visible ? 'checked="checked"' : '' ?> name="visibility">
                                    <label for="visibilityForm" id="labelFormVisibility" data-tooltips="tooltip"
                                           data-placement="top" title="<?= $visible ? 'Make hidden' : 'Make visible' ?>"
                                           class="m-0 btn btn-sm btn-light border-dark">
                                    <i class="fa fa-<?= $visible ? 'eye' : 'eye-slash' ?>"
                                       aria-hidden="true"></i></label>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-2">
                            <textarea class="form-control" id="formContent" name="content" rows="10"
                                      placeholder="Article content"><?= nl2br(htmlspecialchars($article['content'])) ?? '' ?></textarea>
                        <input type="text" class="mt-2 form-control" id="formPseudo" name="author"
                               value="<?= $article['author'] ?? '' ?>" placeholder="Your name">
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="m-0">
                                <small>
                                <?= $edit ?
                                    "<strong>Created by: </strong>". htmlentities($article['author']) . " on " . $article['date_created_format'] .
                                   ($article['date_updated_format'] ? " -- <strong>Updated: </strong>" . $article['date_updated_format'] : "") :
                                    "" ?>
                                </small>
                            </p>
                            <?php if($edit) :?>
                                <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
                            <?php endif; ?>
                            <div>
                                <a href="/admin" class="p-1 btn btn-danger btn-sm"><i class="fa fa-ban" aria-hidden="true"></i> Cancel</a>
                                <button type="submit" class="btn btn-success btn-sm"><?= $edit ? "Save " : "Create " ?><i class="fa fa-check" aria-hidden="true"></i></button>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        const visibilityForm = $('#visibilityForm');
        visibilityForm.on('change', () => {
            const label = $('#labelFormVisibility');
            if (visibilityForm.is(':checked')) {
                label.find('i').toggleClass('fa-eye fa-eye-slash');

                $('.js-visibility-card').toggleClass('border-success border-warning');
                $('.js-visibility-header').toggleClass('bg-success bg-warning');
                label.attr('data-original-title', "Make hidden");
                label.tooltip('hide');
                label.tooltip('show');
            } else {
                label.find('i').toggleClass('fa-eye fa-eye-slash');
                $('.js-visibility-card').toggleClass('border-success border-warning');
                $('.js-visibility-header').toggleClass('bg-success bg-warning');
                label.attr('data-original-title', "Make visible");
                label.tooltip('hide');
                label.tooltip('show');
            }
        })
    </script>
<?php
include '../footer.php';
?>