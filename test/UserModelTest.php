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

require_once __DIR__ . '/data/UserModelTestData.php';

class UserModelTest extends TestCase {

    private DbModel $dbmodel;

    protected function setup(): void {
        $env_dir = getenv(name: "GESUNDHEIT_ENV_DIR");
        $env_file = getenv(name: 'GESUNDHEIT_ENV_FILE');
        Util::loadEnv(dirpath: $env_dir, filename: $env_file);
        $this->dbmodel = DbModel::newDbModel();
        $this->dbmodel->connect();
    }

    public function testUserModel(): void {
        $m = new UserModel();
        print ($m);
        $m->setUsernum(usernum: 1);
        $m->setName(name: 'jax');
        $m->setPassword(password: 'arfarf');
        print ($m);
        $m->save(dbmodel: $this->dbmodel);
        $m1 = new UserModel();
        $m1->load(dbmodel: $this->dbmodel, usernum: 1);
        print ($m1);
        $m1->setName(name: "fred");
        $m1->setPassword(password: "woofwoof");
        $m1->save(dbmodel: $this->dbmodel);
        $m->load(dbmodel: $this->dbmodel, usernum: 1);
        print ($m);
        $m = new UserModel();
        $m->setUsernum(usernum: 2);
        $m->setName(name: 'sumi');
        $m->setPassword(password: 'gravel');
        $m->save(dbmodel: $this->dbmodel);
        $m1 = new UserModel();
        $m1->load(dbmodel: $this->dbmodel, usernum: 2);
        print ($m1);
        $this->expectOutputString(expectedString: UserModelTestData::TEMP_STR);
    }

    protected function tearDown(): void {
        if ($this->dbmodel) {
            $this->dbmodel->close();
        }
        unset($this->dbmodel);
    }
}
