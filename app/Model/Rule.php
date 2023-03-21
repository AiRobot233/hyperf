<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property int $pid
 * @property string $name
 * @property string $type
 * @property string $router
 * @property int $sort
 * @property string $method
 * @property string $tag
 */
class Rule extends Model
{
    public bool $timestamps = false;

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'rule';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'pid' => 'integer', 'sort' => 'integer'];

    public function setFromData(array $data)
    {
        $this->pid = $data['pid'];
        $this->name = $data['name'];
        $this->type = $data['type'];
        $this->router = $data['router'];
        $this->sort = $data['sort'] ?? 0;
        $this->method = $data['method'] ?? null;
        $this->tag = $data['tag'] ?? null;
    }
}
