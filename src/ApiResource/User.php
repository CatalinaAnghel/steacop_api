<?php
declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Dto\User\Output\UserOutputDto;
use App\State\Processor\UserStateProcessor;
use App\State\Provider\UserStateProvider;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ApiResource]
//#[Post(processor: UserStateProcessor::class)]
//#[Get(output: UserOutputDto::class, provider: UserStateProvider::class)]
class User implements PasswordAuthenticatedUserInterface{
    /**
     * @var string $code
     */
    #[ApiProperty(identifier: true)]
    private string $code;

    /**
     * @var string|null $email
     */
    private ?string $email = null;

    /**
     * @var array $roles
     */
    private array $roles = [];

    /**
     * @var string|null $plainPassword
     */
    private ?string $plainPassword;

    /**
     * @var string|null $password
     * The hashed password
     */
    private ?string $password = null;

    /**
     * @var string|null $discriminator
     * The user's type (if any)
     */
    private ?string $discriminator = null;

    /**
     * @return string
     */
    public function getCode(): string {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void {
        $this->code = $code;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(string $email): self {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): self {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): string {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void {
        $this->plainPassword = $plainPassword;
    }


    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    /**
     * @return string|null
     */
    public function getDiscriminator(): ?string {
        return $this->discriminator;
    }

    /**
     * @param string|null $discriminator
     */
    public function setDiscriminator(?string $discriminator): void {
        $this->discriminator = $discriminator;
    }
}
