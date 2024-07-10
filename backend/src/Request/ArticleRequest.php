<?php

namespace App\Request;

use App\Exception\ValidateException;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArticleRequest
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    private function getConstrains(): Constraints\Collection
    {
        return new Constraints\Collection([
            'fields' => [
                'author_id' => [
                    new Constraints\Type(['type' => 'numeric']),
                    new Constraints\Positive(),
                ],
                'viewed' => [
                    new Constraints\Type(['type' => 'numeric']),
                ],
            ],
            'allowMissingFields' => true,
        ]);
    }

    public function validate(Request $request): array
    {
        $data = array_filter([
            'author_id' => $request->query->has('author_id') ? $request->query->get('author_id') : null,
            'viewed' => $request->query->has('viewed') ? $request->query->get('viewed') : null,
        ]);

        $violations = $this->validator->validate($data, $this->getConstrains());
        $this->checkOnErrors($violations);

        return $data;
    }

    public function checkOnErrors(ConstraintViolationListInterface $violations): void
    {
        if (count($violations) > 0) {
            $errors = [];

            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            throw new ValidateException($errors);
        }
    }
}