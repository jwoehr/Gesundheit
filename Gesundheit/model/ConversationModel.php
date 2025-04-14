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

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/PostingModel.php';

/**
 *  ConversationModel is an array of PostingModels
 *
 * @author jwoehr
 */
class ConversationModel {

    private array $postings;

    public function __construct(array $postings = []) {
        $this->setPostings(postings: $postings);
    }

    public function __tostring(): string {
        $out = '';
        foreach ($this->getPostings() as $posting) {
            $out .= strval(value: $posting) . "\n";
        }
        return $out;
    }

    public function getPostings(): array {
        return $this->postings;
    }

    public function setPostings(array $postings): void {
        $this->postings = $postings;
    }

    public function addPosting(PostingModel $postingmodel): void {
        $this->postings[] = $postingmodel;
    }

    /**
     * Yield "doc" (i.e., BSONArray) from this ConversationModel instance
     * @return \MongoDB\Model\BSONArray this ConversationModel instance as BSONArray
     */
    public function toDoc(): MongoDB\Model\BSONArray {
        $postingstodoc = [];
        foreach ($this->getPostings() as $posting) {
            $postingstodoc[] = $posting->toDoc();
        }
        return new MongoDB\Model\BSONArray(array: $postingstodoc);
    }

    /**
     * Instance this ConversationModel from a "doc" (i.e., BSONArray)
     * @param MongoDB\Model\BSONArray $doc
     * @return void
     */
    public function fromDoc(MongoDB\Model\BSONArray $doc): void {
        $postings = [];
        foreach ($doc as $item) {
            $postingmodel = (new PostingModel())->fromDoc(doc: $item);
            $postings[] = $postingmodel;
        }
        $this->setPostings(postings: $postings);
    }

    /**
     * Factory a new ConversationModel from a "doc"  (i.e., BSONArray)
     * @param MongoDB\Model\BSONArray $doc created from a Conversation Model
     * @return ConversationModel factoried instance
     */
    public static function newFromDoc(MongoDB\Model\BSONArray $doc): ConversationModel {
        $conversation = new ConversationModel();
        $conversation->fromDoc(doc: $doc);
        return $conversation;
    }
}
