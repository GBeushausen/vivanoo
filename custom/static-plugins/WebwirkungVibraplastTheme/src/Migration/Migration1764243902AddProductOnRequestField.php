<?php declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

/**
 * @internal
 */
class Migration1764243902AddProductOnRequestField extends MigrationStep
{
    private const FIELD_SET_NAME = 'ww_vibraplast_theme_product_set';
    private const FIELD_NAME = 'ww_vibraplast_product_on_request';

    public function getCreationTimestamp(): int
    {
        return 1764243902;
    }

    public function update(Connection $connection): void
    {
        $setId = $connection->fetchOne(
            "SELECT id FROM custom_field_set WHERE name = :name",
            ['name' => self::FIELD_SET_NAME]
        );

        if (!$setId) {
            throw new \RuntimeException(sprintf('Custom field set "%s" not found', self::FIELD_SET_NAME));
        }

        $config = [
            'componentName' => 'sw-single-select',
            'customFieldType' => 'select',
            'customFieldPosition' => 50,
            'label' => [
                'en-GB' => 'Product on request',
                'de-DE' => 'Produkt auf Anfrage',
            ],
            'placeholder' => [
                'en-GB' => 'Select option',
                'de-DE' => 'Option wählen',
            ],
            'helpText' => [
                'en-GB' => 'Controls visibility of request button and buy box',
                'de-DE' => 'Steuert die Sichtbarkeit von Anfrage-Button und Kaufen-Bereich',
            ],
            'options' => [
                [
                    'label' => [
                        'en-GB' => 'No',
                        'de-DE' => 'Nein',
                    ],
                    'value' => 'no',
                ],
                [
                    'label' => [
                        'en-GB' => 'Yes (product is purchasable)',
                        'de-DE' => 'Ja (Produkt ist kaufbar)',
                    ],
                    'value' => 'yes_purchasable',
                ],
                [
                    'label' => [
                        'en-GB' => 'Yes (product is not purchasable)',
                        'de-DE' => 'Ja (Produkt ist nicht kaufbar)',
                    ],
                    'value' => 'yes_not_purchasable',
                ],
            ],
        ];

        $connection->insert('custom_field', [
            'id' => Uuid::randomBytes(),
            'name' => self::FIELD_NAME,
            'type' => 'select',
            'config' => json_encode($config, \JSON_THROW_ON_ERROR),
            'active' => true,
            'set_id' => $setId,
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // no destructive changes
    }
}
