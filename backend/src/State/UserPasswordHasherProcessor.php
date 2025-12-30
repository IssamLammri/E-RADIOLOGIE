<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserPasswordHasherProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $persistProcessor,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof User) {
            $plain = $data->getPlainPassword();

            // Hash seulement si fourni (POST ou PATCH)
            if ($plain) {
                $data->setPassword($this->passwordHasher->hashPassword($data, $plain));
                $data->eraseCredentials();
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
