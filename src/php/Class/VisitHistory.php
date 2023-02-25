<?php
require_once __DIR__ . '/Validation.php';

class VisitHistory
{
    private string $year;
    private string $month;
    private string $day;
    private string $target_date;
    private int $shop_id;
    private int $customer_id;
    private int $user_id;
    private string $price;
    private string $memo;
    private Validation $validation;

    public function __construct(array $history_data)
    {
        foreach ($history_data as $key => $value) {
            if ($key === 'price') {
                $this->{$key} = extractNumber($value);
            } elseif ($key === 'month' || $key === 'day') {
                if (ctype_digit($value)) {
                    $this->{$key} = sprintf('%02d', $value);
                } else {
                    $this->{$key} = $value;
                }
            } else {
                $this->{$key} = $value;
            }
        }
        $this->target_date = $this->year . '-' . $this->month . '-' . $this->day;

        $this->validation = new Validation();
    }

    public function registerVisitHistory(): void
    {
        $visit_history_id = $this->fetchTargetDateHistoryId();

        $this->validate();

        if (empty($this->getErrors())) {
            if ($visit_history_id) {
                $sql = "UPDATE `visit_histories`
                        SET `date` = :date, `price` = :price, `memo` = :memo
                        WHERE `id` = :id";

                $options = [
                    'id' => $visit_history_id,
                    'date' => $this->target_date,
                    'price' => (int) $this->price,
                    'memo' => $this->memo
                ];
            } else {
                $sql = "INSERT INTO `visit_histories` (`shop_id`, `user_id`, `customer_id`, `date`, `price`, `memo`)
                        VALUES (:shop_id, :user_id, :customer_id, :date, :price, :memo)";

                $options = [
                    'shop_id' => $this->shop_id,
                    'user_id' => $this->user_id,
                    'customer_id' => $this->customer_id,
                    'date' => $this->target_date,
                    'price' => $this->price,
                    'memo' => $this->memo,
                ];
            }
            $mysql = new ExecuteMySql($sql, $options);

            $mysql->execute();
        }
    }

    public function fetchTargetDateHistoryId(): ?int
    {
        $sql = "SELECT id
                FROM `visit_histories`
                WHERE `date` = :date
                AND `customer_id` = :customer_id
                LIMIT 1";

        $options = [
            'date' => $this->target_date,
            'customer_id' => $this->customer_id
        ];

        $mysql = new ExecuteMySql($sql, $options);

        $visit_history_id = isset($mysql->execute()[0]) ? $mysql->execute()[0]['id'] : null;

        return $visit_history_id;
    }

    private function validate(): void
    {
        $data = [
            'date' => $this->target_date,
            'price' => $this->price,
            'memo' => $this->memo,
        ];

        $rules = [
            'date' => 'required|date',
            'price' => 'required|price',
            'memo' => 'maxlength:60',
        ];

        $this->validation->validate($data, $rules);
    }

    public function getErrors(): array
    {
        return $this->validation->getErrors();
    }
}
