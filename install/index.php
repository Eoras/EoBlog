<?php
$errors = "";
$post = [];
$start = false;
if (file_exists('../config/parameters.php')) {
    $errors = "You already have file parameters.php on /config/ folder. Delete it and come back ;)";
} else {
    try {
        $parametersFile = fopen("parameters.php.dist", "w");
        $start = true;
    } catch (Exception $e) {
        $errors = $e->getMessage();
    }
}
$progress = ($start === true) ? 25 : 100;
include 'params.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/fontawesome/web-fonts-with-css/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="icon" type="image/png" href="../assets/favicon.png">
    <script src="../assets/js/jquery-3.3.1.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <title>EoBlog - Install</title>
</head>
<body>

<div class="container py-3">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <h1><a href="/" class="text-dark">EoBlog</a></h1>
                <h3 class="">Installation</h3>
            </div>
        </div>
    </div>

    <div class="sticky-top mt-6 alert-dark border-0 shadow p-2">
        <p class="step step1 m-0 py-2 d-inline">
            <i class="text-<?= $start === true ? 'success' : 'danger' ?> fas fa-<?= $start === true ? 'check' : 'exclamation-triangle' ?>"></i>
            <?= $start === true ? ' Ready to continue' : 'File already exist' ?>
        </p>
        <?php if($start === true) :?>
        <div class="progress">
            <div id='progress' class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuemin="0" aria-valuemax="100">
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="alert alert-dark bg-dark text-light configParam shadow">

        <!-- START -->
        <div class="text-center">
            <img src="../assets/img/eoras.png" alt="Eoras" title="Eöras">
            <h3>
                <i class="far fa-smile" aria-hidden="true"></i>
                Thank you to use my work ;)
                <i class="far fa-smile" aria-hidden="true"></i>
            </h3>
        </div>

        <div class="mt-5">
            <p class="text-center">
                <i class="fas fa-exclamation-circle fa-5x text-danger" aria-hidden="true"></i>
            </p>
            <p>
                <i class="fas fa-arrow-circle-right" aria-hidden="true"></i>
                This will help you to generate the <strong>DATABASE</strong> and all the <strong>TABLE</strong>
                that you need to use this Blog.</p>
            <p class="mb-0">
                <i class="fas fa-arrow-circle-right" aria-hidden="true"></i>
                To start, make you make you sur to have installed:</p>
            <ul class="list">
                <li><i class="fa fa-arrow-right" aria-hidden="true"></i> PHP</li>
                <li><i class="fa fa-arrow-right" aria-hidden="true"></i> MySQL</li>
            </ul>
            <?php if (empty($errors)) : ?>
            <p class="js-collapse2ndStepTop">Is all good ? So click on <strong><i class="fa fa-play text-success"
                                                                                  aria-hidden="true"></i></strong> to
                run
            </p>
            <p class="text-center collapse2ndStep btn btn-success float-right" data-toggle="collapse"
               href="#collapse2ndStep" role="button"
               aria-expanded="false" aria-controls="collapseExample">NEXT
                <i class="fa fa-play" aria-hidden="true"></i>
            </p>
            <div class="clearfix"></div>
        </div>

        <!-- NEXT 2 -->
        <div class="collapse" id="collapse2ndStep">
            <p>
                <i class="fas fa-arrow-circle-right" aria-hidden="true"></i>
                To create and configure the database correctly, I need the following information
            </p>
            <form id="formParam">
                <fieldset id="fs">
                    <!--DATABASE PARAM-->
                    <div class="card text-dark">
                        <div class="card-header">
                            <p class="m-0">DATABASE</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md">
                                    <div class="form-group">
                                        <label for="pDB_Name">Name of DataBase</label>
                                        <input type="text" name="pDB_Name" id="pDB_Name" class="form-control"
                                               value="<?= $parameters['pDB_Name']['value'] ?>">
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <label for="pDB_UserName">UserName</label>
                                        <input type="text" name="pDB_UserName" id="pDB_UserName" class="form-control"
                                               value="<?= $parameters['pDB_UserName']['value'] ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pDB_Password">Password</label>
                                        <input type="password" name="pDB_Password" id="pDB_Password"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--BLOG PARAM-->
                    <div class="card text-dark mt-3">
                        <div class="card-header">
                            <p class="m-0">BLOG</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md">
                                    <div class="form-group">
                                        <label for="pBlog_Mark">Mark Name</label>
                                        <input type="text" name="pBlog_Mark" id="pBlog_Mark" class="form-control"
                                               value="<?= $parameters['pBlog_Mark']['value'] ?>">
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <label for="pBlog_Title">Blog title</label>
                                        <input type="text" name="pBlog_Title" id="pBlog_Title" class="form-control"
                                               value="<?= $parameters['pBlog_Title']['value'] ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md">
                                    <div class="form-group">
                                        <label for="pBlog_NbArticlesPerPage">Number of articles per page</label>
                                        <input type="number" name="pBlog_NbArticlesPerPage" id="pBlog_NbArticlesPerPage"
                                               class="form-control"
                                               value="<?= $parameters['pBlog_NbArticlesPerPage']['value'] ?>">
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <label for="pBlog_NbCommentsPerPage">Number of comments per page</label>
                                        <input type="text" name="pBlog_NbCommentsPerPage" id="pBlog_NbCommentsPerPage"
                                               class="form-control"
                                               value="<?= $parameters['pBlog_NbCommentsPerPage']['value'] ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md">
                                    <div class="form-group">
                                        <label for="pBlog_MinLengthAuthor">Minimum Length Pseudo</label>
                                        <input type="text" name="pBlog_MinLengthAuthor" id="pBlog_MinLengthAuthor"
                                               class="form-control"
                                               value="<?= $parameters['pBlog_MinLengthAuthor']['value'] ?>">
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <label for="pBlog_MinLengthComment">Minimum Length Comment</label>
                                        <input type="text" name="pBlog_MinLengthComment" id="pBlog_MinLengthComment"
                                               class="form-control"
                                               value="<?= $parameters['pBlog_MinLengthComment']['value'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--ADMIN-->
                    <div class="card text-dark mt-3">
                        <div class="card-header">
                            <p class="m-0">ADMIN</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md">
                                    <div class="form-group">
                                        <label for="pAdmin_NbArticlesPerPage">Number of articles per page</label>
                                        <input type="number" name="pAdmin_NbArticlesPerPage"
                                               id="pAdmin_NbArticlesPerPage" class="form-control"
                                               value="<?= $parameters['pAdmin_NbArticlesPerPage']['value'] ?>">
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <label for="pAdmin_NbCommentsPerPage">Number of comments per page</label>
                                        <input type="text" name="pAdmin_NbCommentsPerPage" id="pAdmin_NbCommentsPerPage"
                                               class="form-control"
                                               value="<?= $parameters['pAdmin_NbCommentsPerPage']['value'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--PASSWORD ADMIN-->
                    <div class="card text-dark mt-3">
                        <div class="card-header">
                            <p class="m-0">PASSWORD ADMIN</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pAdmin_password">Choose a password:</label>
                                        <input type="password" name="pAdmin_password"
                                               id="pAdmin_password" class="form-control"
                                               value="<?= $parameters['pAdmin_password']['value'] ?>">
                                        <small><strong>Password by default:</strong> admin</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="alert alert-danger mt-3 ">
                    <i class="fas fa-exclamation-triangle text-danger" aria-hidden="true"></i> If the database already exists, all data will be deleted
                </div>
                <button type="submit" id="formParamBtnSubmit" class="text-center btn btn-success float-right">
                    LET'S GO
                    <i class="fa fa-play" aria-hidden="true"></i>
                </button>
                <div class="clearfix"></div>
                <div class="clearfix"></div>
            </form>
            <div class="errors"></div>
        </div>
        <?php else: ?>
            <div class="alert alert-danger">
                <p class="m-0 font-weight-bold">Something looks bad :/</p>
                <p class="m-0"><?= nl2br($errors) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        let isStart = <?= $start ?>;

        const step2 = $('#collapse2ndStep');
        const form = $('#formParam');
        const progress =  $('#progress');
        progress.width(0).width('25%').html('25%');

        step2.on('show.bs.collapse', function () {
            $('.collapse2ndStep').fadeOut(200);
        });
        step2.on('shown.bs.collapse', function () {
            const id = this.getAttribute('id');
            $('html, body').animate({
                scrollTop: $('.js-' + id + 'Top').offset().top
            }, 1000);
            $('.step1').after('<p class="step step2 m-0 py-2 d-inline">-- <i class="fa fa-spinner fa-spin text-primary"></i> Configuration</p>');
            $('.step2').hide().fadeIn(500);
            progress.width('50%').html('50%');

        });

        let sendPost = false;
        let savedForm;
        form.submit(function (e) {
            $.ajax({
                type: "POST",
                url: "/install/install.php",
                data: form.serialize(),
                beforeSend: (e) => {
                    if (sendPost === false) {
                        savedForm = form.clone(true);
                        $('#fs').attr('disabled', 'disabled');
                        $("#formParamBtnSubmit").addClass('disabled').append(" <i class='fas fa-spinner fa-spin waiting'></i>").find('.fa-play').remove();
                        console.log('Doing ...');
                        sendPost = true;
                    }
                },
                success: (data) => {
                    data = JSON.parse(data);
                    if (data.error) {
                        $('.errors').html(`
                            <div class="alert alert-danger errorAlert fixed-top m-5 alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <p class="m-0 font-weight-bold">Error:</p>
                                <p class="m-0">` + data.error + `</p>
                            </div>`).hide().fadeIn(400);
                        $('.step2').find('i.fa-spin').removeClass('fa fa-spinner fa-spin text-primary').addClass('text-danger fa-exclamation-triangle fas')
                        $('#fs').removeAttr('disabled');
                        $("#formParamBtnSubmit").removeClass('disabled').append('<i class="fa fa-play" aria-hidden="true"></i>').find('i.waiting').remove();
                        sendPost = false;
                        setTimeout(() => {
                            $(".errorAlert").alert('close')
                        }, 10000)
                    } else if (data.success) {
                        $('.configParam div').not(':first').remove();
                        progress.width('100%').html('100%');
                        $('.configParam div').append(
                            `<div class="row validation">
                            <div class="col-12 text-center">
                                <span class="fa fa-check text-success fa-10x" data-fa-transform="shrink-8 right-6"
                                aria-hidden="true"></span>
                                <p>Congratulations the configuration is complete</p>
                                <div class="mt-5 mb-2 d-flex align-items-center justify-content-center">
                                    <i class="far fa-4x text-danger fa-arrow-alt-circle-right mr-4 d-li"></i>
                                    <h3 class="m-0"><a href="/" class="font-weight-bold text-light">GO TO MY BLOG</a></h3>
                                    <i class="ml-4 far fa-4x text-danger fa-arrow-alt-circle-left"></i>
                                </div>
                            </div>
                        </div>
                            `);
                        $('.step2').find('i').removeClass().addClass('text-success fa-check fas');
                        setTimeout(()=> {
                            window.location.href = '/';
                        },10000);
                        sendPost = false;
                    }
                }, error(e) {
                    console.log(e);
                }
            });
            e.preventDefault();
        });
    </script>
</div>
</body>
</html>
