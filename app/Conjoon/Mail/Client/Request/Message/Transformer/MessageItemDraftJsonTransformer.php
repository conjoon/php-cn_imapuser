<?php
/**
 * conjoon
 * php-cn_imapuser
 * Copyright (C) 2019 Thorsten Suckow-Homberg https://github.com/conjoon/php-cn_imapuser
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
 * USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
declare(strict_types=1);

namespace Conjoon\Mail\Client\Request\Message\Transformer;

use Conjoon\Mail\Client\Request\JsonTransformer,
    Conjoon\Mail\Client\Request\JsonTransformerException,
    Conjoon\Mail\Client\Message\MessageItemDraft;

/**
 * Interface provides contract for processing an associative array containing
 * plain data to a MessageItemDraft.
 *
 * @package Conjoon\Mail\Client\Request\Message\Transformer
 */
interface MessageItemDraftJsonTransformer extends JsonTransformer {

    /**
     * Returns a MessageItemDraft that was created from the data found in $data.
     * id, mailAccountId and mailFolderId are mandatory fields. This method will
     * throw an exception if any of these fields are missing.
     *
     * @param array $data
     * @return MessageItemDraft
     *
     * @throws JsonTransformerException
     */
    public function transform(array $data) : MessageItemDraft;

}