<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Project;
use App\Entity\Student;
use App\Entity\Supervisor;
use Doctrine\ORM\EntityManagerInterface;

class CodeGeneratorService
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {

    }

    /**
     * Generates the project unique code based on the student and the supervisor's data
     * @param string $studentName
     * @param string $supervisorName
     * @return string|null
     */
    public function generateUniqueCode(string $studentName, string $supervisorName): string{
        $supervisorCode = strtoupper(substr($supervisorName, 0, 2));
        $studentCode = strtoupper(substr($studentName, 0, 2));
        $repo = $this->entityManager->getRepository(Project::class);
        $found = false;
        $code = null;
        while(!$found){
            $randString = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);

            $code = $supervisorCode . $studentCode . $randString;
            $exists = $repo->count(['code' => $code]) > 0;

            if(!$exists){
                $found = true;
            }
        }

        return $code;
    }
}
