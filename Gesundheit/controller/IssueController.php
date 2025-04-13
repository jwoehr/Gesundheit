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

    /**
     * Create IssueModel from database entry
     * @param MongoDB\Model\BSONDocument $doc
     * @return IssueModel
     */
    public static function issueModelFromDoc(MongoDB\Model\BSONDocument $doc): IssueModel {
        $issuemodel = new IssueModel();
        $issuemodel->fromDoc($doc);
        return $issuemodel;
    }

    /**
     * Fetch an issue from database, if it exists
     * @param int $issue_number
     * @param DbModel $dbmodel
     * @return IssueModel|null
     */
    public static function getIssue(int $issue_number, DbModel $dbmodel): ?IssueModel {
        $issuemodel = null;
        $issue_array = $dbmodel->issue_user_lookup($issue_number);
        if (!empty($issue_array)) {
            $doc = $issue_array[0];
            $issuemodel = self::issueModelFromDoc($doc);
        }
        return $issuemodel;
    }

    /**
     * Get an array of IssueModel for all issues
     * @param DbModel $dbmodel
     * @return array
     */
    public static function getAllIssues(DbModel $dbmodel): array {
        $allIssues = [];
        $issue_user_lookup_all = $dbmodel->issue_user_lookup_all();
        foreach ($issue_user_lookup_all as $doc) {
            $allIssues[] = self::issueModelFromDoc($doc);
        }
        return $allIssues;
    }

    /**
     *  Apply next highest number to an IssueModel
     * @param IssueModel $issuemodel
     * @param DbModel $dbmodel
     */
    public static function numberNewIssue(IssueModel $issuemodel, DbModel $dbmodel): void {
        $issuemodel->setIssue_number($dbmodel->highestIssueNumber() + 1);
    }

    /**
     *  Create/save a new issue assigning it the next highest issue number
     * @param string $description
     * @param UserModel $usermodel
     * @param DbModel $dbmodel
     * @return bool
     */
    public static function saveNewIssue(string $description, UserModel $usermodel, DbModel $dbmodel): bool {
        $issuemodel = new IssueModel();
        $issuemodel->setUsernum($usermodel->getUsernum());
        $issuemodel->setName($usermodel->getName());
        $issuemodel->setDescription($description);
        self::numberNewIssue($issuemodel, $dbmodel);
        return $dbmodel->upsert_issue($issuemodel->toDoc());
    }
}
