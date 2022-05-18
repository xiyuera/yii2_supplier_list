<?php

namespace app\models;

use phpDocumentor\Reflection\Types\Static_;
use Yii;

/**
 * This is the model class for table "suppliers".
 *
 * @property int $id
 * @property string $name
 * @property string|null $code
 * @property int|null $t_status
 * @property string $status
 */
class Supplier extends \yii\db\ActiveRecord
{
    const STATUS_HOLD = 1;
    const STATUS_OK = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'suppliers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['t_status'], 'integer'],
            [['t_status'], 'in', 'range' => [static::STATUS_HOLD, static::STATUS_OK]],
            [['name'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 3],
            [['code'], 'unique'],
            [['status', 'in', 'range' => [1, 2]]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            't_status' => 'Status',
            'status' => 'Status',
        ];
    }

    public function getStatus()
    {
        switch ($this->t_status) {
            case static::STATUS_HOLD:
                return 'hold';

            case static::STATUS_OK:
                return 'ok';

            default:
                return '';
        }
    }

    public function setStatus($status)
    {
        switch ($status) {
            case 'hold':
                $this->t_status = static::STATUS_HOLD;
                break;
            case 'ok':
                $this->t_status = static::STATUS_OK;
                break;
            default:
                break;
        }
    }
}
