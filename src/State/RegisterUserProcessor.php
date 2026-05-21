<?php

namespace App\State;

use ApiPlatform\Doctrine\Orm\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterUserProcessor implements ProcessorInterface
{

    public function __construct(
        private ProcessorInterface $persistProcessor,
        private UserPasswordHasherInterface $hasher
    ) {}

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $hashedPassword = $this->hasher->hashPassword($data, $data->getPassword());
        $data->setPassword($hashedPassword);

        $data->setRoles(['ROLE_USER']);
        $data->setIsVerified(false);

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
