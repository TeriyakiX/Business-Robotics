<?php

declare(strict_types=1);

namespace App\Exceptions\Article;

use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response;

final class ArticlePublishForbiddenException extends BaseException
{
    protected string $messageKey = 'article.publish_forbidden';
    protected int $statusCode = Response::HTTP_BAD_REQUEST;
}
