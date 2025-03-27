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

/**
 * IssueModel is the issue model, application-specific building blocks 
 * implemented in MongoDB.
 *
 * @author jwoehr
 */
class IssueModel {

    private int $issue_number;
    private int $usernum;
    private string $description;
    private array $conversation;
    private bool $resolved;

    public function __construct(int $issue_number = 0, int $usernum = 0, string $description = "", array $conversation = [], bool $resolved = false) {
        $this->setIssue_number(issue_number: $issue_number);
        $this->setUsernum(usernum: $usernum);
        $this->setDescription(description: $description);
        $this->setConversation(conversation: $conversation);
        $this->setResolved(resolved: $resolved);
    }

    public function __toString(): string {
        return "IssueModel[issue_number=" . $this->issue_number
                . ", usernum=" . $this->usernum
                . ", description=" . $this->description
                . ", conversation=" . $this->conversation
                . ", resolved=" . $this->resolved
                . "]";
    }

    public function getIssue_number(): int {
        return $this->issue_number;
    }

    public function getUsernum(): int {
        return $this->usernum;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getConversation(): array {
        return $this->conversation;
    }

    public function getResolved(): bool {
        return $this->resolved;
    }

    public function setIssue_number(int $issue_number): void {
        $this->issue_number = $issue_number;
    }

    public function setUsernum(int $usernum): void {
        $this->usernum = $usernum;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setConversation(array $conversation): void {
        $this->conversation = $conversation;
    }

    public function setResolved(bool $resolved): void {
        $this->resolved = $resolved;
    }

    public function toDoc(): MongoDB\Model\BSONDocument {
        $doc = new MongoDB\Model\BSONDocument(
                [
            'issue_number' => $this->getIssue_number(),
            'usernum' => $this->getUsernum(),
            'description' => $this->getDescription(),
            'conversation' => $this->getConversation(),
            'resolved' => $this->getResolved()
        ]);
        return $doc;
    }

    public function fromDoc(MongoDB\Model\BSONDocument $doc): void {
        $this->setIssue_number(issue_number: $doc->issue_number);
        $this->setUsernum(usernum: $doc->usernum);
        $this->setDescription(description: $doc->description);
        $this->setConversation(conversation: $doc->conversation);
        $this->setResolved(resolved: $doc->resolved);
    }

    public function load(int $issue_number, DbModel $dbmodel): bool {
        $success = false;
        $doc = $dbmodel->get_issue_by_issue_number(issue_number: $issue_number);
        if ($doc) {
            $this->fromDoc(doc: $doc);
            $success = true;
        }
        return $success;
    }

    public function save(DbModel $dbmodel): bool {
        return $dbmodel->upsert_issue(doc: $this->toDoc());
    }
}
