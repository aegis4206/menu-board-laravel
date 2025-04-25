<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * 統一格式化所有日期欄位（如 created_at, updated_at 等）
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return (new \DateTime($date->format('Y-m-d H:i:s'), $date->getTimezone()))
            ->setTimezone(new \DateTimeZone('Asia/Taipei'))
            ->format('Y-m-d H:i:s');
    }
}
