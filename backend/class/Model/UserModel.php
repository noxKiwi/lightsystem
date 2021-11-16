<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Model;

use noxkiwi\dataabstraction\Model;

/**
 * I am the storage for different failure classes
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class UserModel extends Model
{
    public const TABLE           = 'user';
    public const ID              = 'user_id';
    public const CREATED         = 'user_created';
    public const MODIFIED        = 'user_modified';
    public const FLAGS           = 'user_flags';
    public const USERNAME        = 'user_username';
    public const EMAIL           = 'user_email';
    public const PASSWORD        = 'user_password';
    public const FAILED_ATTEMPTS = 'user_failed_attempts';
    public const FIELDS          = [
        self::ID,
        self::CREATED,
        self::MODIFIED,
        self::FLAGS,
        self::USERNAME,
        self::EMAIL,
        self::PASSWORD,
        self::FAILED_ATTEMPTS
    ];
    const        LAST_LOGIN      = 'user_lastlogin';
}
