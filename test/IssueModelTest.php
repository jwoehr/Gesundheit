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

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/data/IssueModelTestData.php';

class IssueModelTest extends TestCase {

    private DbModel $dbmodel;

    protected function setup(): void {
        $env_dir = getenv(name: "GESUNDHEIT_ENV_DIR");
        $env_file = getenv(name: 'GESUNDHEIT_ENV_FILE');
        Util::loadEnv(dirpath: $env_dir, filename: $env_file);
        $this->dbmodel = DbModel::newDbModel();
        $this->dbmodel->connect();
    }

    public function testIssueModel(): void {
        $issue = new IssueModel();
        $this->assertEquals(expected: IssueModelTestData::ISSUE_NEW, actual: print_r(value: $issue, return: true));
        $issue->setIssue_number(issue_number: 1);
        $issue->setUsernum(usernum: 2);
        $issue->setDescription(description: "This is a test issue");
        $p0 = new PostingModel(usernum: 2, posting: "I had an issue");
        $p1 = new PostingModel(usernum: 1, posting: "I fixed the issue");
        $issue->setConversation(conversation: new ConversationModel(postings: [$p0, $p1]));
        $issue->setResolved(resolved: true);
        $this->assertEquals(expected: IssueModelTestData::ISSUE_WITH_DATA, actual: print_r(value: $issue, return: true));
        $issue->save(dbmodel: $this->dbmodel);
        $issue1 = new IssueModel();
        $this->assertTrue(condition: $issue1->load(dbmodel: $this->dbmodel, issue_number: 1));
        $this->assertEquals(expected: IssueModelTestData::ISSUE_WITH_DATA, actual: print_r(value: $issue1, return: true));
        $issue1->setIssue_number(issue_number: 2);
        $issue1->setUsernum(usernum: 1);
        $issue1->setDescription(description: "This is another test issue");
        $p2 = new PostingModel(usernum: 1, posting: "I had a new issue");
        $p3 = new PostingModel(usernum: 1, posting: "I have not fixed the new issue");
        $issue1->setConversation(conversation: new ConversationModel(postings: [$p2, $p3]));
        $issue1->setResolved(resolved: false);
        $this->assertEquals(expected: IssueModelTestData::ISSUE1_WITH_DATA, actual: print_r(value: $issue1, return: true));
        $issue1->save($this->dbmodel);
        $issue->load(dbmodel: $this->dbmodel, issue_number: 2);
        $this->assertEquals(expected: IssueModelTestData::ISSUE1_WITH_DATA, actual: print_r(value: $issue1, return: true));
    }

    protected function tearDown(): void {
        if ($this->dbmodel) {
            $this->dbmodel->close();
        }
        unset($this->dbmodel);
    }
}
