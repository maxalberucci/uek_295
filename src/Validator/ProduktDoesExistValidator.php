<?php

namespace App\Validator;

use App\Repository\ProduktRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProduktDoesExistValidator extends ConstraintValidator
{
    public function __construct(private ProduktRepository $repository){}
    public function validate($idProdukt, Constraint $constraint){
        $produkt = $this->repository->find($idProdukt);
        If(!$produkt){
            $this->context
                ->buildViolation($constraint->message())
                ->setParameter("{{produktId}}", $idProdukt)
                ->addViolation();
        }
    }
}