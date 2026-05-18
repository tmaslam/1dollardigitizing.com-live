<?php

namespace App\Support;

class SiteContext
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $legacyKey,
        public readonly string $slug,
        public readonly string $name,
        public readonly string $brandName,
        public readonly string $host,
        public readonly string $supportEmail,
        public readonly string $fromEmail,
        public readonly string $websiteAddress,
        public readonly bool $isPrimary,
        public readonly string $activePaymentProvider = '',
        public readonly string $timezone = 'UTC',
        public readonly string $companyAddress = '',
        public readonly string $phoneNumber = '',
        public readonly string $ukPhoneNumber = '',
        public readonly string $ukAddress = '',
        public readonly string $pkPhoneNumber = '',
        public readonly string $pkAddress = '',
    ) {
    }

    public function phoneForTel(): string
    {
        return preg_replace('/[^0-9+]/', '', $this->phoneNumber) ?? '';
    }

    public function ukPhoneForTel(): string
    {
        return preg_replace('/[^0-9+]/', '', $this->ukPhoneNumber) ?? '';
    }

    public function pkPhoneForTel(): string
    {
        return preg_replace('/[^0-9+]/', '', $this->pkPhoneNumber) ?? '';
    }

    public function displayLabel(): string
    {
        return $this->brandName !== '' ? $this->brandName : $this->name;
    }

    public function matchesLegacyKey(?string $legacyKey): bool
    {
        return $legacyKey !== null && strcasecmp($this->legacyKey, trim($legacyKey)) === 0;
    }
}
