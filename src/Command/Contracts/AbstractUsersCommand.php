<?php
declare(strict_types=1);

namespace App\Command\Contracts;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

abstract class AbstractUsersCommand extends Command {
    public const IMPORT_FILE_PATH = '\\..\\..\\..\\public\\media\\';

    /**
     * @var string $fileName
     */
    private string $fileName;

    /**
     * @return string
     */
    public function getImportFilePath(): string {
        return __DIR__ . static::IMPORT_FILE_PATH;
    }

    /**
     * @return array
     */
    public function mapCSV(): array{
        $userData = [];
        $userDataType = [];
        $rowCounter = 0;
        $handle = fopen($this->getImportFilePath() . $this->fileName, 'r');
        while (false !== ($data = fgetcsv($handle))) {
            if (0 === $rowCounter) {
                $userDataType = $data;
            } else {
                $temp = [];
                foreach ($data as $key => $value) {
                    $temp[$userDataType[$key]] = $value;
                }
                $userData[] = $temp;
            }
            $rowCounter++;
        }
        return $userData;
    }

    /**
     * @param string $fileName
     * @return void
     */
    public function setFileName(string $fileName): void {
        $this->fileName = $fileName;
    }
}