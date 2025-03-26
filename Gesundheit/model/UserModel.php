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
require_once(__DIR__ . '/../util/Util.php');
require_once (__DIR__ . '/DbModel.php');

/**
 * UserModel is the user model, application-specific building blocks 
 * implemented in MongoDB.
 *
 * @author jwoehr
 */
class UserModel {

    private ?int $usernum;
    private ?string $name;
    private ?string $password;

    public function __construct(int $usernum = 0, string $name = "", string $password = "") {
        $this->usernum = $usernum;
        $this->name = $name;
        $this->password = $password;
    }

    public function __toString(): string {
        return "UserModel[usernum=" . $this->usernum
                . ", name=" . $this->name
                . ", password=" . $this->password
                . "]";
    }

    public function getUsernum(): ?int {
        return $this->usernum;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function setUsernum(?int $usernum): void {
        $this->usernum = $usernum;
    }

    public function setName(?string $name): void {
        $this->name = $name;
    }

    public function setPassword(?string $password): void {
        $this->password = $password;
    }

    public function toDoc(): MongoDB\Model\BSONDocument {
        $doc = new MongoDB\Model\BSONDocument(
                [
            'usernum' => $this->getUsernum(),
            'name' => $this->getName(),
            'password' => $this->getPassword(),
        ]);
        return $doc;
    }

    public function fromDoc(MongoDB\Model\BSONDocument $doc): void {
        $this->setUsernum($doc->usernum);
        $this->setName($doc->name);
        $this->setPassword($doc->password);
    }

    protected function loadByName(string $name, DbModel $dbmodel): bool {
        $success = false;
        $doc = $dbmodel->get_userdoc_by_name($name);
        if ($doc) {
            $this->fromDoc($doc);
            $success = true;
        }
        return $success;
    }

    protected function loadByUsernum(int $usernum): bool {
        $success = false;
        $doc = $dbmodel->get_userdoc_by_usernum($usernum);
        if ($doc) {
            $this->fromDoc($doc);
            $success = true;
        }
        return $success;
    }

    public function load(?string $name, ?int $usernum, DbModel $dbmodel): bool {
        $success = false;
        if ($name) {
            $success = $this->loadByName($name, $dbmodel);
        } elseif ($usernum) {
            $success = $this->loadByUsernum($usernum, $dbmodel);
        }
        return $success;
    }

    public function save(DbModel $dbmodel): bool {
        return $dbmodel->upsert_userdoc($this->toDoc());
    }
}
