<?php

namespace App\Validator;

use App\Repository\KommentareRepository;
use PhpParser\Node\Stmt\If_;
use Symfony\Component\Validator\Constraint;

class KommentareDoseExistValidator
{
    public function __construct(private KommentareRepository $repository){}
    public function validate($idKommentare, Constraint $constraint){
        $kommentare = $this->repository->find($idKommentare);
        If(!$kommentare){
            $this->context
                ->bildViolation($constraint->message)
                ->setParameter("{{kommentareId}}", $idKommentare)
                ->addViolation();
        }
    }
}