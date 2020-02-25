<?php
declare(strict_types=1);

namespace App\Model\Latte;

use App\Model\Database\Entity\User;
use Nette\Bridges\ApplicationLatte\Template;

/**
 * @property-read User $userLoggedIn
 * @property-read string $filePath
 */
final class TemplateProperty extends Template
{

}