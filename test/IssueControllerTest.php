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

require_once __DIR__ . '/data/IssueControllerTestData.php';
require_once __DIR__ . '/data/IssueModelTestData.php';

class IssueControllerTest extends TestCase {

    private DbModel $dbmodel;

    protected function setup(): void {
        $env_dir = getenv("GESUNDHEIT_ENV_DIR");
        $env_file = getenv('GESUNDHEIT_ENV_FILE');
        Util::loadEnv($env_dir, $env_file);
        $this->dbmodel = DbModel::newDbModel();
        $this->dbmodel->connect();
    }

    public function testGetIssue1(): void {
        $this->assertEquals(expected: IssueControllerTestData::ISSUE_1, actual: print_r(value: IssueController::getIssue(1, $this->dbmodel), return: true));
    }

    public function testGetAllIssues(): void {
        $this->assertEquals(expected: IssueControllerTestData::ALL_ISSUES, actual: print_r(value: IssueController::getAllIssues($this->dbmodel), return: true));
    }

    public function testNumberNewIssue(): void {
        $im = new IssueModel();
        $this->assertEquals(expected: IssueModelTestData::ISSUE_NEW, actual: print_r(value: $im, return: true));
        IssueController::numberNewIssue($im, $this->dbmodel);
        $this->assertEquals(expected: IssueControllerTestData::ISSUE_MODEL_NUMBERED_3, actual: print_r(value: $im, return: true));
    }

    protected function tearDown(): void {
        if ($this->dbmodel) {
            $this->dbmodel->close();
        }
        unset($this->dbmodel);
    }
}
