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

/**
 * Data for IssueControllerTest
 *
 * @author jwoehr
 */
class IssueControllerTestData {

    const ISSUE_1 = 'IssueModel Object
(
    [issue_number:IssueModel:private] => 1
    [usernum:IssueModel:private] => 2
    [name:IssueModel:private] => sumi
    [description:IssueModel:private] => This is a test issue
    [conversation:IssueModel:private] => ConversationModel Object
        (
            [postings:ConversationModel:private] => Array
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

        )

    [resolved:IssueModel:private] => 1
)
';
    const ALL_ISSUES = 'Array
(
    [0] => IssueModel Object
        (
            [issue_number:IssueModel:private] => 1
            [usernum:IssueModel:private] => 2
            [name:IssueModel:private] => sumi
            [description:IssueModel:private] => This is a test issue
            [conversation:IssueModel:private] => ConversationModel Object
                (
                    [postings:ConversationModel:private] => Array
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

                )

            [resolved:IssueModel:private] => 1
        )

    [1] => IssueModel Object
        (
            [issue_number:IssueModel:private] => 2
            [usernum:IssueModel:private] => 1
            [name:IssueModel:private] => fred
            [description:IssueModel:private] => This is another test issue
            [conversation:IssueModel:private] => ConversationModel Object
                (
                    [postings:ConversationModel:private] => Array
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

                )

            [resolved:IssueModel:private] => 
        )

)
';
    const ISSUE_MODEL_NUMBERED_3 = <<< END
IssueModel Object
(
    [issue_number:IssueModel:private] => 3
    [usernum:IssueModel:private] => 0
    [name:IssueModel:private] => 
    [description:IssueModel:private] => 
    [conversation:IssueModel:private] => ConversationModel Object
        (
            [postings:ConversationModel:private] => Array
                (
                )

        )

    [resolved:IssueModel:private] => 
)

END;
}
