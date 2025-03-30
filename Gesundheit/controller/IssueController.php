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

require_once __DIR__ . "/../model/DbModel.php";
require_once __DIR__ . "/../model/UserModel.php";
require_once __DIR__ . "/../model/IssueModel.php";
require_once __DIR__ . "/../model/PostingModel.php";
require_once __DIR__ . "/../model/ConversationModel.php";

class IssueController {

    public static function issueModelFromDoc(MongoDB\Model\BSONDocument $doc): IssueModel {
        $issuemodel = new IssueModel();
        $issuemodel->fromDoc($doc);
        return $issuemodel;
    }

    public static function getIssue(int $issue_number, DbModel $dbmodel): ?IssueModel {
        $issuemodel = null;
        $issue_array = $dbmodel->issue_user_lookup($issue_number);
        if (!empty($issue_array)) {
            $doc = $issue_array[0];
            $issuemodel = self::issueModelFromDoc($doc);
        }
        return $issuemodel;
    }

    public static function getAllIssues(DbModel $dbmodel): array {
        $allIssues = [];
        $issue_user_lookup_all = $dbmodel->issue_user_lookup_all();
        foreach ($issue_user_lookup_all as $doc) {
            $allIssues[] = self::issueModelFromDoc($doc);
        }
        return $allIssues;
    }
}
