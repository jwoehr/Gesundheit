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
 * Data for ConversationModelTest
 * 
 * @author jwoehr
 */
class ConversationModelTestData {

    const C_NEW = <<<END
ConversationModel Object
(
    [postings:ConversationModel:private] => Array
        (
        )

)

END;
    const C_WITH_POSTINGS = <<<END
ConversationModel Object
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

END;
    const C_TO_DOC = <<< END
MongoDB\Model\BSONArray Object
(
    [storage:ArrayObject:private] => Array
        (
            [0] => MongoDB\Model\BSONDocument Object
                (
                    [storage:ArrayObject:private] => Array
                        (
                            [usernum] => 2
                            [posting] => I had an issue
                        )

                )

            [1] => MongoDB\Model\BSONDocument Object
                (
                    [storage:ArrayObject:private] => Array
                        (
                            [usernum] => 1
                            [posting] => I fixed the issue
                        )

                )

        )

)
END;
    const D_DOC = <<<END
MongoDB\Model\BSONArray Object
(
    [storage:ArrayObject:private] => Array
        (
            [0] => MongoDB\Model\BSONDocument Object
                (
                    [storage:ArrayObject:private] => Array
                        (
                            [usernum] => 2
                            [posting] => I had an issue
                        )

                )

            [1] => MongoDB\Model\BSONDocument Object
                (
                    [storage:ArrayObject:private] => Array
                        (
                            [usernum] => 1
                            [posting] => I fixed the issue
                        )

                )

        )

)

END;
    const C1_FROM_DOC = <<< END
ConversationModel Object
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

END;
}
