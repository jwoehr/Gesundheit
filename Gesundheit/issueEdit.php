<!DOCTYPE html>
<!--
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
-->

<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/util/Util.php';
require_once __DIR__ . '/model/DbModel.php';
require_once __DIR__ . '/controller/IssueEditController.php';
require_once __DIR__ . '/controller/LoginController.php';
require_once __DIR__ . '/view/IssueEditView.php';

$dotenv = Util::loadEnv(dirpath: __DIR__ . '/../..');
$docroot = Util::getDotEnv(key: 'docroot');
$mongodb_uri = Util::getDotEnv(key: 'mongodb_uri');
$mongodb_db_name = Util::getDotEnv(key: 'mongodb_db_name');
$dbmodel = DbModel::newDbModel();
$dbmodel->connect();
$usermodel = LoginController::validateLoginCookie(dbmodel: $dbmodel);
if (!$usermodel) {
    $dbmodel->close();
    header("Location: ./login.php");
} else {
    $issuenumber = IssueEditController::httpIssueNumber();
    if ($issuenumber) {
        if (IssueEditController::isResolve()) {
            IssueEditController::resolveIssue(issuenumber: $issuenumber, dbmodel: $dbmodel);
        } elseif (IssueEditController::isSave()) {
            IssueEditController::savePosting(issuenumber: $issuenumber, usermodel: $usermodel, text: IssueEditController::httpPosting(), dbmodel: $dbmodel);
        }
    }
    ?>
    <html>

        <head>
            <meta charset="UTF-8">
            <link rel="icon" type="image/png" href="./favico.png">
            <title>Gesundheit Issue Editor</title>
            <?php print Util::stylesheet(url: './css/trackertable.css') ?>
        </head>

        <body>
            <div>
                <?php
                print Util::htmlHTag(level: 1, text: "Gesundheit Issue Editor");
                print IssueEditView::issueEditTable(dbmodel: $dbmodel, issue_number: $issuenumber);
                print Util::htmlSavePosting(issuenumber: $issuenumber);
                print Util::htmlIssueResolve(issuenumber: $issuenumber);
                print Util::htmlIssueView() . PHP_EOL;
                print Util::htmlLogout() . PHP_EOL;
                $dbmodel->close();
                ?>
            </div>

        </body>

    </html>
    <?php
}
