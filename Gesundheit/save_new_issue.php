<?php

/*
 * The MIT License
 *
 * Copyright 2025 jwoehr.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/util/Util.php';
require_once __DIR__ . '/model/DbModel.php';
require_once __DIR__ . '/controller/LoginController.php';
require_once __DIR__ . '/controller/IssueController.php';
require_once __DIR__ . '/view/IssueView.php';

$dotenv = Util::loadEnv(dirpath: __DIR__ . '/../..');
$docroot = Util::getDotEnv(key: 'docroot');
$mongodb_uri = Util::getDotEnv(key: 'mongodb_uri');
$mongodb_db_name = Util::getDotEnv(key: 'mongodb_db_name');
$dbmodel = DbModel::newDbModel();
$dbmodel->connect();
$usermodel = LoginController::validateLoginCookie(dbmodel: $dbmodel);
if (!$usermodel) {
    $dbmodel->close();
    header(header: "Location: ./login.php");
} else {
    $description = IssueView::getNewIssueDescription();
    $posting = IssueView::getNewIssuePosting();
    IssueController::saveNewIssue(description: $description, text: $posting, usermodel: $usermodel, dbmodel: $dbmodel);
    $dbmodel->close();
    header(header: "Location: ./index.php");
}