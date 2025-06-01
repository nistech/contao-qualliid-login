<?php

declare(strict_types=1);

namespace Nistech\ContaoQualliIdLogin\OAuth2\Token;

use Contao\BackendUser;
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\FrontendUser;
use Contao\User;
use Contao\Validator;
use Doctrine\DBAL\Connection;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Markocupic\ContaoOAuth2Client\OAuth2\Token\TokenHandlerInterface;
use Psr\Log\LoggerInterface;

class QualliIdTokenHandler implements TokenHandlerInterface
{
    /**
     * The claim where the user identifier is stored.
     */
    private string $claim = 'UserName';

    /**
     * tl_user.email or tl_member.email.
     */
    private string $contaoIdentifierFieldName = 'username';
    private LoggerInterface $logger;
    private LoggerInterface $generalLogger;

    public function __construct(
        protected readonly ContaoFramework $framework,
        protected readonly Connection $connection,
        private readonly LoggerInterface $contaoErrorLogger,
        private readonly LoggerInterface $contaoGeneralLogger,
    ) {
        $this->logger=$contaoErrorLogger;
        $this->generalLogger=$contaoGeneralLogger;
    }

    /**
     * Return all supported oauth clients
     * e.g. ['github_backend', 'github_frontend'].
     *
     * @return array<string>
     */
    public function supports(): array
    {
        return ['qualliid_backend', 'qualliid_frontend'];
    }

    public function getUserBadgeFromResourceOwner(ResourceOwnerInterface $resourceOwner, string $firewall): UserBadge|null
    {
        $claims = $resourceOwner->toArray();

        [$userClass, $table] = match ($firewall) {
            'contao_backend' => [BackendUser::class, 'tl_user'],
            default => [FrontendUser::class, 'tl_member'],
        };

        $user = $this->getContaoUserFromClaims($claims, $table, $userClass);

        // Test if login is allowed
        if (empty($user)) {
            $this->logger->error("Contao user not found. Claims: ".print_r($claims, true));
            return null;
        }
        if (!$this->canLogin($user)) {
            $this->logger->error("Contao user cannot login. User: ".print_r($user, true));
            return null;
        }

        $this->generalLogger->info("Qualli.Id: user badge created. Claims: ".print_r($claims, true));

        return new UserBadge($user->getUserIdentifier());
    }

    protected function getContaoUserFromClaims(array $claims, string $table, string $userClass): User|null
    {
        if (empty($claims[$this->claim])) {
            $this->logger->error("Claim ".$this->claim." is empty. Claims: ".print_r($claims, true));
            return null;
        }

        $identifierClaim = $claims[$this->claim];

        if (!$this->getValidator()->isEmail($identifierClaim)) {
            $this->logger->error("Validation failed on isEmail for identifier claim ".$identifierClaim.". Claims: ".print_r($claims, true));
            return null;
        }

        $qb = $this->connection->createQueryBuilder();

        $qb->select('t.username')
            ->from($table, 't')
            ->where($qb->expr()->like("t.$this->contaoIdentifierFieldName", $qb->expr()->literal($identifierClaim)))
        ;

        $userIdentifier = $qb->fetchOne();

        if (!$userIdentifier) {
            $this->logger->error("userIdentifier not found in database. Identifier claim ".$identifierClaim.". Claims: ".print_r($claims, true));
            return null;
        }

        /** @var User $userProvider */
        $userProvider = $this->framework->getAdapter($userClass);

        return $userProvider->loadUserByIdentifier($userIdentifier);
    }

    protected function canLogin(User $user): bool
    {
        if ($user->disable) {
            return false;
        }

        if ('' !== $user->start && (int) $user->start > time()) {
            return false;
        }

        if ('' !== $user->stop && (int) $user->stop < time()) {
            return false;
        }

        if ($user instanceof FrontendUser) {
            if (!$user->login) {
                return false;
            }
        }

        return true;
    }

    protected function getValidator(): Adapter
    {
        return $this->framework->getAdapter(Validator::class);
    }
}