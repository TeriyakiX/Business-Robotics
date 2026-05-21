<?php

declare(strict_types=1);

namespace App\Exceptions\Article;

use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response;

final class ArticleNotFoundException extends BaseException
{
    protected string $messageKey = 'article.not_found';
    protected int $statusCode = Response::HTTP_NOT_FOUND;
}
