<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements DataPersisterInterface
{

    /** @var EntityManagerInterface */
    private $em ;

    /** @var UserPasswordEncoderInterface */
    private $encoder ;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em ;
        $this->encoder = $encoder ;
    }

    /**
     * @inheritDoc
     */
    public function supports($data): bool
    {
       return $data instanceof User ;
    }

    /**
     * @inheritDoc
     */
    public function persist($data)
    {
        if($data->getPassword()):
            $data->setPassword(
                $this->encoder->encodePassword($data, $data->getPassword())
            );
        endif;
        $this->em->persist($data);
        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function remove($data)
    {
        $this->em->remove($data);
        $this->em->flush();    }
}