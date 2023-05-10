<?php
declare(strict_types=1);

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\SystemSetting\SystemSettingDto;
use App\Dto\SystemSetting\SystemSettingInputDto;
use App\Dto\SystemSetting\SystemSettingsCollectionDto;
use App\Entity\SystemSetting;
use App\Helper\SystemSettingsHelper;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class SystemSettingsStateProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $logger
    ) {}

    /**
     * @inheritDoc
     * @param SystemSettingInputDto $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []):
    SystemSettingsCollectionDto
    {
        $settingsOutputDto = new SystemSettingsCollectionDto();
        $settingsRepo = $this->entityManager->getRepository(SystemSetting::class);
        $milestoneSetting = $settingsRepo
            ->findOneBy(['name' => SystemSettingsHelper::MilestoneMeetingsLimit->getName()]);
        $milestoneSetting?->setValue((float)$data->getMilestoneMeetingsLimit());
        $assignmentPenalizationSetting = $settingsRepo
            ->findOneBy(['name' => SystemSettingsHelper::AssignmentPenalization->getName()]);
        $assignmentPenalizationSetting?->setValue((float)$data->getAssignmentPenalization());
        try {
            $this->entityManager->flush();

            $config = new AutoMapperConfig();
            $config->registerMapping(SystemSetting::class,
                SystemSettingDto::class
            );
            $mapper = new AutoMapper($config);

            if (null !== $milestoneSetting) {
                $settingsOutputDto->addSetting(
                    $mapper->map($milestoneSetting, SystemSettingDto::class)
                );
            }

            if (null !== $assignmentPenalizationSetting) {
                $settingsOutputDto->addSetting(
                    $mapper->map($assignmentPenalizationSetting, SystemSettingDto::class)
                );
            }

        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return $settingsOutputDto;
    }
}
