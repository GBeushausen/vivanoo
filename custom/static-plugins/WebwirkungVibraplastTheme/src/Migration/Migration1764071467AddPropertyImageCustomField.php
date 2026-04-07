<?php 
declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1764071467AddPropertyImageCustomField extends MigrationStep
{
    private const FIELD_SET_NAME = 'ww_vibraplast_theme_property_group_set';
    private const PROPERTY_GROUP_ICON_CUSTOM_FIELD = 'ww_vibraplast_theme_property_group_icon';

    public function getCreationTimestamp(): int
    {
        return 1764071467;
    }

    public function update(Connection $connection): void
    {
        $setId = $connection->fetchOne(
            'SELECT id FROM custom_field_set WHERE name = :name',
            ['name' => self::FIELD_SET_NAME]
        );

        if (!$setId) {
            throw new \RuntimeException(sprintf('Custom field set "%s" not found', self::FIELD_SET_NAME));
        }

        $config = [
            'customFieldType' => 'media',
            'customFieldPosition' => 1,
            'label' => [
                'en-GB' => 'Property group icon',
                'de-DE' => 'Eigenschaftsgruppe Icon',
            ],
            'componentName' => 'sw-media-field',
        ];

        $connection->insert('custom_field', [
            'id' => Uuid::randomBytes(),
            'name' => self::PROPERTY_GROUP_ICON_CUSTOM_FIELD,
            'type' => 'media',
            'config' => json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'set_id' => $setId,
            'created_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
        ]);
    }
}
