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

require_once __DIR__ . '/data/ConversationModelTestData.php';

class ConversationModelTest extends TestCase {

    private DbModel $dbmodel;

    protected function setup(): void {
        $env_dir = getenv("GESUNDHEIT_ENV_DIR");
        $env_file = getenv('GESUNDHEIT_ENV_FILE');
        Util::loadEnv($env_dir, $env_file);
        $this->dbmodel = DbModel::newDbModel();
        $this->dbmodel->connect();
    }

    public function testConversationModel() {
        $c = new ConversationModel();
        $this->assertEquals(ConversationModelTestData::C_NEW, print_r($c, true));
        $p0 = new PostingModel(2, "I had an issue");
        $p1 = new PostingModel(1, "I fixed the issue");
        $c->addPosting($p0);
        $c->addPosting($p1);
        $this->assertEquals(ConversationModelTestData::C_WITH_POSTINGS, print_r($c, true));
        $d = $c->toDoc();
        $this->assertEquals(ConversationModelTestData::D_DOC, print_r($d, true));
        $c1 = ConversationModel::newFromDoc($d);
        $this->assertEquals(ConversationModelTestData::C1_FROM_DOC, print_r($c1, true));
    }

    protected function tearDown(): void {
        if ($this->dbmodel) {
            $this->dbmodel->close();
        }
        unset($this->dbmodel);
    }
}
