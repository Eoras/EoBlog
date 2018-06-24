<?php

$parameters = [
    'pDB_Name' => ['value' => 'EoBlog_DB', 'type' => 'STRING'],
    'pDB_UserName' => ['value' => 'root', 'type' => 'STRING'],
    'pDB_Password' => ['value' => '', 'type' => 'STRING'],
    'pBlog_Mark' => ['value' => 'My Blog', 'type' => 'STRING'],
    'pBlog_Title' => ['value' => 'Hello', 'type' => 'STRING'],
    'pBlog_NbArticlesPerPage' => ['value' => 5, 'type' => 'INT'],
    'pBlog_NbCommentsPerPage' => ['value' => 10, 'type' => 'INT'],
    'pBlog_MinLengthAuthor' => ['value' => 3, 'type' => 'INT'],
    'pBlog_MinLengthComment' => ['value' => 10, 'type' => 'INT'],
    'pAdmin_NbArticlesPerPage' => ['value' => 5, 'type' => 'INT'],
    'pAdmin_NbCommentsPerPage' => ['value' => 50, 'type' => 'INT'],
    'pAdmin_password' => ['value' => 'admin', 'type' => 'STRING'],
];
$types = ['INT', 'STRING'];