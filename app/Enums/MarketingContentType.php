<?php

namespace App\Enums;

enum MarketingContentType: string
{
    case PromoCopy = 'promo_copy';
    case IgCaption = 'ig_caption';
    case TiktokCaption = 'tiktok_caption';
    case WaBroadcast = 'wa_broadcast';
    case Hashtags = 'hashtags';
    case Poster = 'poster';

    public function label(): string
    {
        return match ($this) {
            self::PromoCopy => 'Copy Promosi',
            self::IgCaption => 'Caption Instagram',
            self::TiktokCaption => 'Caption TikTok',
            self::WaBroadcast => 'Pesan Broadcast WA',
            self::Hashtags => 'Ide Hashtag',
            self::Poster => 'Poster Promo',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PromoCopy => '📝',
            self::IgCaption => '📸',
            self::TiktokCaption => '🎵',
            self::WaBroadcast => '💬',
            self::Hashtags => '#️⃣',
            self::Poster => '🎨',
        };
    }
}
