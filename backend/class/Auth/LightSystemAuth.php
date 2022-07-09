<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Auth;

use noxkiwi\core\Auth;
use noxkiwi\core\Request;
use noxkiwi\lightsystem\Model\UserModel;
use function date;
use function password_verify;

/**
 * I am the DataClient.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class LightSystemAuth extends Auth
{
    private const MAX_ATTEMPTS = 5;
    /**
     * @var \noxkiwi\lightsystem\Model\UserModel
     */
    private UserModel $userModel;

    /**
     * lightsystemAuth constructor.
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    protected function __construct()
    {
        parent::__construct();
        $this->userModel = UserModel::getInstance();
    }

    /**
     * @inheritDoc
     */
    public function authenticate(string $userName, string $password): array
    {
        $this->userModel->addFilter(UserModel::EMAIL, $userName);
        $user = $this->userModel->search();
        if (empty($user)) {

            return ['A' => 'B'];
        }
        $user = $user[0];
        if ((int)$user[UserModel::FLAGS] !== 1) {

            return [];
        }
        $user[UserModel::FLAGS] = 1;
        if (! password_verify($this->passwordMake($userName, $password), $user[UserModel::PASSWORD])) {
            if ($user[UserModel::FAILED_ATTEMPTS] >= self::MAX_ATTEMPTS) {
                $user[UserModel::FLAGS] = 0;
            }
            $user[UserModel::FAILED_ATTEMPTS]++;
            $this->userModel->save($user);

            return [];
        }
        $user[UserModel::FAILED_ATTEMPTS] = 0;
        $user[UserModel::LAST_LOGIN]      = date('Y-m-d H:i:s');
        $this->userModel->save($user);

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function passwordMake(string $username, string $password): string
    {
        return $username . $password . 'sdoifh048rhoprtgasfv';
    }

    /**
     * @inheritDoc
     */
    public function memberOf(string $groupName): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getLoginUrl(Request $request): string
    {
        return '/';
    }
}
