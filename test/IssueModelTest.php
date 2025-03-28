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

class IssueModelTest extends TestCase {

    private DbModel $dbmodel;

    protected function setup(): void {
        $env_dir = getenv("GESUNDHEIT_ENV_DIR");
        $env_file = getenv('GESUNDHEIT_ENV_FILE');
        Util::loadEnv($env_dir, $env_file);
        $this->dbmodel = DbModel::newDbModel();
        $this->dbmodel->connect();
    }

    public function testIssueModel() {
        $issue = new IssueModel();
        print($issue . PHP_EOL);
        $issue->setIssue_number(1);
        $issue->setUsernum(2);
        $issue->setDescription("This is a test issue");
        $conversation = [];
        $conversation[] = new PostingModel(2, "I had an issue");
        $conversation[] = new PostingModel(1, "I fixed the issue");
        $issue->setConversation($conversation);
        $issue->setResolved(true);
        print($issue . PHP_EOL);
        $issue->save($this->dbmodel);
        $issue1 = new IssueModel();
        $issue1->load(dbmodel: $this->dbmodel, issue_number: 1);
        print($issue1 . PHP_EOL);
        $issue1->setIssue_number(2);
        $issue1->setUsernum(1);
        $issue1->setDescription("This is another test issue");
        $conversation = [];
        $conversation[] = new PostingModel(1, "I had a new issue");
        $conversation[] = new PostingModel(1, "I have not fixed the new issue");
        $issue1->setConversation($conversation);
        $issue1->setResolved(false);
        print($issue1 . PHP_EOL);
        $issue1->save($this->dbmodel);
        $issue->load(dbmodel: $this->dbmodel, issue_number: 2);
        print($issue . PHP_EOL);
        $output_string = <<<END
IssueModel[issue_number=0, usernum=0, description=, conversation=Array
(
)
, resolved=false]
IssueModel[issue_number=1, usernum=2, description=This is a test issue, conversation=Array
(
    [0] => PostingModel Object
        (
            [usernum:PostingModel:private] => 2
            [posting:PostingModel:private] => I had an issue
        )

    [1] => PostingModel Object
        (
            [usernum:PostingModel:private] => 1
            [posting:PostingModel:private] => I fixed the issue
        )

)
, resolved=true]
IssueModel[issue_number=1, usernum=2, description=This is a test issue, conversation=Array
(
    [0] => PostingModel Object
        (
            [usernum:PostingModel:private] => 2
            [posting:PostingModel:private] => I had an issue
        )

    [1] => PostingModel Object
        (
            [usernum:PostingModel:private] => 1
            [posting:PostingModel:private] => I fixed the issue
        )

)
, resolved=true]
IssueModel[issue_number=2, usernum=1, description=This is another test issue, conversation=Array
(
    [0] => PostingModel Object
        (
            [usernum:PostingModel:private] => 1
            [posting:PostingModel:private] => I had a new issue
        )

    [1] => PostingModel Object
        (
            [usernum:PostingModel:private] => 1
            [posting:PostingModel:private] => I have not fixed the new issue
        )

)
, resolved=false]
IssueModel[issue_number=2, usernum=1, description=This is another test issue, conversation=Array
(
    [0] => PostingModel Object
        (
            [usernum:PostingModel:private] => 1
            [posting:PostingModel:private] => I had a new issue
        )

    [1] => PostingModel Object
        (
            [usernum:PostingModel:private] => 1
            [posting:PostingModel:private] => I have not fixed the new issue
        )

)
, resolved=false]

END;
        $this->expectOutputString($output_string);
    }

    protected function tearDown(): void {
        if ($this->dbmodel) {
            $this->dbmodel->close();
        }
        unset($this->dbmodel);
    }
}
