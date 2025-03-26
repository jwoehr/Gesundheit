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

    private ?int $issue_number;
    private ?string $user;
    private ?string $description;
    private ?string $conversation;
    private ?string $resolved;

    public function getIssue_number(): ?int {
        return $this->issue_number;
    }

    public function getUser(): ?string {
        return $this->user;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function getConversation(): ?string {
        return $this->conversation;
    }

    public function getResolved(): ?string {
        return $this->resolved;
    }

    public function setIssue_number(?int $issue_number): void {
        $this->issue_number = $issue_number;
    }

    public function setUser(?string $user): void {
        $this->user = $user;
    }

    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    public function setConversation(?string $conversation): void {
        $this->conversation = $conversation;
    }

    public function setResolved(?string $resolved): void {
        $this->resolved = $resolved;
    }

    public function load(?int $issue_number): bool {
        $success = false;
        return $success;
    }

    public function save(): bool {
        $success = false;
        return $success;
    }
}
