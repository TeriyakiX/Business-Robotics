<?php

declare(strict_types=1);

namespace App\Exceptions\Article;

use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response;

final class ArticleSlugExistsException extends BaseException
{
    protected string $messageKey = 'article.slug_exists';
    protected int $statusCode = Response::HTTP_CONFLICT;
}
