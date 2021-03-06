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

namespace Conjoon\Mail\Client;

use Conjoon\Mail\Client\Data\CompoundKey\MessageKey,
    Conjoon\Mail\Client\Data\CompoundKey\FolderKey,
    Conjoon\Mail\Client\Data\MailAccount,
    Conjoon\Mail\Client\Attachment\FileAttachmentList,
    Conjoon\Mail\Client\Message\MessageItem,
    Conjoon\Mail\Client\Message\MessageItemDraft,
    Conjoon\Mail\Client\Message\MessageBody,
    Conjoon\Mail\Client\Message\MessageBodyDraft,
    Conjoon\Mail\Client\Message\MessageItemList,
    Conjoon\Mail\Client\Message\Flag\FlagList,
    Conjoon\Mail\Client\Folder\MailFolderList;

/**
 * Interface MailClient
 * @package Conjoon\Mail\Client
 */
interface MailClient {


    /**
     * Returns a MailFolderList with ListMailFolders representing all
     * mailboxes available for the specified MailAccount.
     *
     * @param MailAccount $mailAccount
     *
     * @return MailFolderList
     *
     * @throws MailClientException if any exception occurs
     */
    public function getMailFolderList(MailAccount $mailAccount) :MailFolderList;


    /**
     * Returns the specified MessageItem for the submitted arguments.
     *
     * @param MessageKey $key
     *
     * @return MessageItem|null The MessageItem or null if none found.
     *
     * @throws MailClientException if any exception occurs
     */
     public function getMessageItem(MessageKey $key) :?MessageItem;


    /**
     * Returns the specified MessageItemDraft for the submitted arguments.
     *
     * @param MessageKey $key
     *
     * @return MessageItemDraft|null The MessageItemDraft or null if none found.
     *
     * @throws MailClientException if any exception occurs
     */
    public function getMessageItemDraft(MessageKey $key) :?MessageItemDraft;


    /**
     * Returns the specified MessageBody for the submitted arguments.
     *
     * @param MessageKey $key
     *
     * @return MessageBody The MessageBody or null if none found.
     *
     * @throws MailClientException if any exception occurs
     */
    public function getMessageBody(MessageKey $key) :?MessageBody;


    /**
     * Appends a new Message to the specified Folder with the data found in MessageBodyDraft.
     * Will mark the newly created Message as a draft.
     *
     * @param FolderKey $key
     * @param MessageBodyDraft $messageBodyDraft
     *
     * @return MessageBodyDraft the created MessageBodyDraft
     *
     * @throws MailClientException if any exception occurs, or of the MessageBodyDraft already has
     * a MessageKey
     */
    public function createMessageBodyDraft(FolderKey $key, MessageBodyDraft $messageBodyDraft) :MessageBodyDraft;


    /**
     * Updates the MessageBody of the specified message.
     *
     * @param MessageBodyDraft $messageBodyDraft
     *
     * @return MessageBodyDraft the created MessageBodyDraft
     *
     * @throws MailClientException if any exception occurs, or of the MessageBodyDraft does not have a
     * MessageKey
     */
    public function updateMessageBodyDraft(MessageBodyDraft $messageBodyDraft) :MessageBodyDraft;


    /**
     * Updates envelope information of a Message, if the Message is a draft Message.
     *
     * @param MessageItemDraft $messageItemDraft
     *
     * @return MessageItemDraft The MessageItemDraft updated, along with its MessageKey, which
     * might not equal to the MessageKey in $messageItemDraft.
     *
     * @throws MailClientException if any exception occurs
     */
    public function updateMessageDraft(MessageItemDraft $messageItemDraft) :?MessageItemDraft;


    /**
     * Returns the specified MessageList for the submitted arguments.
     *
     * @param FolderKey $key
     * @param array|null $options An additional set of options for querying the MessageList, such
     * as sort-direction or start/limit values.
     *
     * @return MessageItemList
     *
     * @throws MailClientException if any exception occurs
     */
    public function getMessageItemList(FolderKey $key , array $options = null) :MessageItemList;


    /**
     * Returns the total number of messages in the specified $mailFolderId for the specified $account;
     *
     * @param FolderKey $key
     *
     * @return int
     *
     * @throws MailClientException if any exception occurs
     */
    public function getTotalMessageCount(FolderKey $key) : int;


    /**
     * Returns the total number of UNRWAD messages in the specified $mailFolderId for the specified $account;
     *
     * @param FolderKey $key
     *
     * @return int
     *
     * @throws MailClientException if any exception occurs
     */
    public function getUnreadMessageCount(FolderKey $key) : int;


    /**
     * Returns the FileAttachments in an FileAttachmentList for the specified message.
     *
     * @param MessageKey $key
     *
     * @return FileAttachmentList
     *
     * @throws MailClientException if any exception occurs
     */
    public function getFileAttachmentList(MessageKey $key) : FileAttachmentList;


    /**
     * Sets the flags specified in FlagList for the message represented by MessageKey.
     *
     * @param MessageKey $key
     * @param FlagList $flagList
     *
     * @return bool if the operatoin succeeded, otherwise false
     *
     * @throws MailClientException if any exception occurs
     */
    public function setFlags(MessageKey $key, FlagList $flagList) : bool;

}