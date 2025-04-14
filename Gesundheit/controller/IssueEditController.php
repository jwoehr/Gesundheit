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

require_once __DIR__ . '/../util/Util.php';
require_once __DIR__ . '/../model/DbModel.php';
require_once __DIR__ . '/../model/UserModel.php';
require_once __DIR__ . '/../controller/IssueController.php';

/**
 * IssueEditController provides routines for the IssueEditView and issueEdit.php
 *
 * @author jwoehr
 */
class IssueEditController {

    public static function httpIssueNumber(): int {
        $my_issue_number = (filter_input(type: INPUT_GET, var_name: 'issue', filter: FILTER_SANITIZE_NUMBER_INT) ?:
                filter_input(type: INPUT_POST, var_name: 'issue', filter: FILTER_SANITIZE_NUMBER_INT));
        return $my_issue_number ?: 0;
    }

    public static function httpPosting(): string {
        $my_posting = (filter_input(type: INPUT_GET, var_name: 'posting', filter: FILTER_SANITIZE_STRING) ?:
                filter_input(type: INPUT_POST, var_name: 'posting', filter: FILTER_SANITIZE_STRING));
        return $my_posting ?: "";
    }

    public static function isResolve(): bool {
        $resolve = (filter_input(type: INPUT_GET, var_name: 'resolve', filter: FILTER_SANITIZE_STRING) ?:
                filter_input(type: INPUT_POST, var_name: 'resolve', filter: FILTER_SANITIZE_STRING));
        return $resolve ? true : false;
    }

    public static function resolveIssue(int $issuenumber, DbModel $dbmodel): bool {
        $issueModel = IssueController::issueModelFromDoc(doc: $dbmodel->get_issue_by_issue_number(issue_number: $issuenumber));
        $issueModel->setResolved(resolved: true);
        return $dbmodel->upsert_issue(doc: $issueModel->toDoc());
    }

    public static function isSave(): bool {
        $save = (filter_input(type: INPUT_GET, var_name: 'save', filter: FILTER_SANITIZE_STRING) ?:
                filter_input(type: INPUT_POST, var_name: 'save', filter: FILTER_SANITIZE_STRING));
        return $save ? true : false;
    }

    public static function savePosting(int $issuenumber, UserModel $usermodel, string $text, DbModel $dbmodel): bool {
        $issuemodel = IssueController::issueModelFromDoc(doc: $dbmodel->get_issue_by_issue_number(issue_number: $issuenumber));
        $posting = new PostingModel(usernum: $usermodel->getUsernum(), posting: $text);
        $issuemodel->getConversation()->addPosting(postingmodel: $posting);
        return $dbmodel->upsert_issue(doc: $issuemodel->toDoc());
    }
}
