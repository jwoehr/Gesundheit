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
 * THE SOFTWARE.s
 */
require_once __DIR__ . '/../../vendor/autoload.php';

class PostingModel {

    private int $usernum;
    private string $posting;

    public function __construct(int $usernum = 0, string $posting = "") {
        $this->setUsernum(usernum: $usernum);
        $this->setPosting(posting: $posting);
    }

    public function __toString(): string {
        $out = "{$this->getUsername()} said:&nbsp;{$this->getPosting()}";
        return $out;
    }

    public function getUsernum(): int {
        return $this->usernum;
    }

    public function getUsername(): string {
        $dbmodel = DbModel::newDbModel();
        $dbmodel->connect();
        $username = $dbmodel->get_userdoc_by_usernum(usernum: $this->getUsernum())['name'];
        $dbmodel->close();
        return $username;
    }

    public function getPosting(): string {
        return $this->posting;
    }

    public function setUsernum(int $usernum): void {
        $this->usernum = $usernum;
    }

    public function setPosting(string $posting): void {
        $this->posting = $posting;
    }

    /**
     * Yield doc from this ConversationModel instance
     * @return \MongoDB\Model\BSONDocument this ConversationModel instance as BSONDocument
     */
    public function toDoc(): MongoDB\Model\BSONDocument {
        return new MongoDB\Model\BSONDocument(input: ['usernum' => $this->getUsernum(), 'posting' => $this->getPosting()]);
    }

    public function fromDoc(MongoDB\Model\BSONDocument $doc): PostingModel {
        $this->setUsernum(usernum: $doc->usernum);
        $this->setPosting(posting: $doc->posting);
        return $this;
    }
}
