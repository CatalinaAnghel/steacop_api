<?php
declare(strict_types=1);

namespace App\Dto\SystemSetting;

class SystemSettingDto
{
    /**
     * @var string $name
     */
    private string $name;

    /**
     * @var float $value
     */
    private float $value;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param float $value
     */
    public function setValue(float $value): void
    {
        $this->value = $value;
    }
}
