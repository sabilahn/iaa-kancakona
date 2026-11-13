<?php

use App\Filters\AuthFilter;
use App\Filters\RoleFilter;
use App\Libraries\EnvConfig;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class AuthSecurityTest extends CIUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        session()->destroy();
    }

    public function testEnvConfigLoadsDatabaseName(): void
    {
        $this->assertSame('dharmaia_kancakona', EnvConfig::get('DB_NAME'));
    }

    public function testLazyMd5PasswordUpgradeCallsUpdatePassword(): void
    {
        $upgraded = [];

        $model = new class ($upgraded) extends UserModel {
            /** @var array<string, string> */
            private array $captured;

            public function __construct(array &$captured)
            {
                $this->captured = &$captured;
            }

            public function updatePassword(string $userId, string $newHash): bool
            {
                $this->captured = ['user_id' => $userId, 'hash' => $newHash];

                return true;
            }
        };

        $user = [
            'user_id'  => 'opr',
            'username' => 'operator',
            'userpass' => md5('rahasia123'),
        ];

        $this->assertTrue($model->verifyAndUpgradePassword($user, 'rahasia123'));
        $this->assertSame('opr', $upgraded['user_id']);
        $this->assertTrue(password_verify('rahasia123', $upgraded['hash']));
        $this->assertFalse($model->verifyAndUpgradePassword($user, 'salah'));
    }

    public function testBcryptPasswordVerifiesWithoutRehash(): void
    {
        $model = new class () extends UserModel {
            public bool $updated = false;

            public function __construct()
            {
            }

            public function updatePassword(string $userId, string $newHash): bool
            {
                $this->updated = true;

                return true;
            }
        };

        $hash = password_hash('rahasia123', PASSWORD_DEFAULT);
        $user = ['user_id' => 'admin', 'userpass' => $hash];

        $this->assertTrue($model->verifyAndUpgradePassword($user, 'rahasia123'));
        $this->assertFalse($model->updated);
        $this->assertFalse($model->verifyAndUpgradePassword($user, 'wrong'));
    }

    public function testAuthFilterRedirectsWhenUnauthenticated(): void
    {
        $filter = new AuthFilter();
        $result = $filter->before(service('request'));

        $this->assertInstanceOf(RedirectResponse::class, $result);
    }

    public function testRoleFilterRejectsNonSuperUser(): void
    {
        session()->set(['status' => 'login', 'role' => 'middle_user']);

        $filter = new RoleFilter();
        $result = $filter->before(service('request'), ['super_user']);

        $this->assertInstanceOf(RedirectResponse::class, $result);
    }

    public function testRoleFilterAllowsSuperUser(): void
    {
        session()->set(['status' => 'login', 'role' => 'super_user']);

        $filter = new RoleFilter();
        $result = $filter->before(service('request'), ['super_user']);

        $this->assertNull($result);
    }
}

