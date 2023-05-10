<?php
declare(strict_types=1);

namespace App\Dto\SystemSetting;

class SystemSettingsCollectionDto
{
    /**
     * @var SystemSettingDto[] $settings
     */
    private array $settings;

    public function __construct() {
        $this->settings = [];
    }

    /**
     * @return SystemSettingDto[]
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * @param  SystemSettingDto[] $settings
     */
    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }

    /**
     * @param SystemSettingDto $setting
     * @return void
     */
    public function addSetting(SystemSettingDto $setting): void
    {
        $this->settings[] = $setting;
    }
}
