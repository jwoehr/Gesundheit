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
require_once __DIR__ . '/../controller/IssueController.php';

/**
 * IssueView draws views of IssueModel
 *
 * @author jwoehr
 */
class IssueView {

    public static function issueTableRow(IssueModel $issuemodel): string {
        $postings = "";
        foreach ($issuemodel->getConversation()->getPostings() as $posting) {
            $postings .= "<p>{$posting}</p>";
        }
        $data = [
            strval(value: $issuemodel->getIssue_number()),
            strval(value: $issuemodel->getUsernum()),
            strval(value: $issuemodel->getName()),
            strval(value: $issuemodel->getDescription()),
            $postings,
            $issuemodel->getResolved() ? "yes" : "no",
        ];
        return Util::htmlTableRow(data: $data);
    }

    public static function issueTableRows(DbModel $dbmodel): string {
        $issuetablerows = "";
        $issuemodels = IssueController::getAllIssues(dbmodel: $dbmodel);
        foreach ($issuemodels as $issuemodel) {
            $issuetablerows .= self::issueTableRow(issuemodel: $issuemodel) . PHP_EOL;
        }
        return $issuetablerows;
    }

    public static function issueTable(DbModel $dbmodel): string {
        $output = '
        <table>
            <caption>
                Issues
            </caption>
            <thead>
                <tr>
                    <th scope="col">Issue number</th>
                    <th scope="col">User number</th>
                    <th scope="col">User name</th>
                    <th scope="col">Issue title</th>
                    <th scope="col">Conversation</th>
                    <th scope="col">Resolved?</th>
                </tr>
            </thead>' . PHP_EOL;

        $output .= self::issueTableRows(dbmodel: $dbmodel);
        $output .= '</table>';
        return $output;
    }

    public static function getNewIssueDescription(): ?string {
        return filter_input(type: INPUT_POST, var_name: 'issue_description', filter: FILTER_SANITIZE_STRING);
    }

    public static function getNewIssuePosting(): ?string {
        return filter_input(type: INPUT_POST, var_name: 'posting', filter: FILTER_SANITIZE_STRING);
    }
}
