<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\MarqueeItem;
use Illuminate\Database\Seeder;

final class MarqueeItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Битрикс24',
            'AmoCRM',
            'Telegram',
            '1С',
            'Salesforce',
            'WhatsApp Business',
            'Asterisk',
            'Mango Office',
            'Zoom Phone',
            'Google Workspace',
            'Slack',
            'Notion',
        ];

        foreach ($items as $index => $name) {
            MarqueeItem::query()->updateOrCreate(
                [MarqueeItem::NAME => $name],
                [
                    MarqueeItem::NAME => $name,
                    MarqueeItem::SORT_ORDER => $index,
                    MarqueeItem::IS_ACTIVE => true,
                ]
            );
        }
    }
}
