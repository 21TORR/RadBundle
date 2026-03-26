<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Security;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Torr\Rad\Security\AbilitiesVoter;

/**
 * @internal
 */
final class AbilitiesVoterTest extends TestCase
{
	/**
	 *
	 */
	public function testVoteGrantedForReachableAbility () : void
	{
		$token = $this->createToken(["ROLE_ADMIN"]);
		$roleHierarchy = new RoleHierarchy(["ROLE_ADMIN" => ["CAN_VIEW_DASHBOARD"]]);
		$voter = new AbilitiesVoter($roleHierarchy);

		self::assertSame(
			VoterInterface::ACCESS_GRANTED,
			$voter->vote($token, null, ["CAN_VIEW_DASHBOARD"]),
		);
	}

	/**
	 *
	 */
	public function testVoteDeniedForMissingAbility () : void
	{
		$token = $this->createToken(["ROLE_USER"]);
		$roleHierarchy = new RoleHierarchy([]);
		$voter = new AbilitiesVoter($roleHierarchy);

		self::assertSame(
			VoterInterface::ACCESS_DENIED,
			$voter->vote($token, null, ["CAN_EDIT_USERS"]),
		);
	}

	/**
	 *
	 */
	public function testVoteAbstainsForNonCanAttribute () : void
	{
		$token = $this->createToken(["ROLE_USER"]);
		$roleHierarchy = new RoleHierarchy([]);
		$voter = new AbilitiesVoter($roleHierarchy);

		self::assertSame(
			VoterInterface::ACCESS_ABSTAIN,
			$voter->vote($token, null, ["ROLE_USER"]),
		);
	}

	/**
	 *
	 */
	private function createToken (array $roles) : UsernamePasswordToken
	{
		return new UsernamePasswordToken(
			new InMemoryUser("test", null, $roles),
			"main",
			$roles,
		);
	}
}
