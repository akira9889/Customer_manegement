<?php
require_once __DIR__ . '/../lib/ExecuteMySql.php';
require_once __DIR__ . '/Validation.php';

class CustomerList
{
    public const PAGE_COUNT = 10;

    public ?int $prev_right;

    public ?int $next_left;

    public int $shop_id;

    public int $count;

    public function __construct(int $shop_id, int $count)
    {
        $this->shop_id = $shop_id;
        $this->count = $count;
    }

    public function fetchCustomerList(): array
    {
        $sql = "SELECT *
                FROM `customers`
                WHERE `shop_id` = :shop_id
                ORDER BY `id` ASC
                LIMIT " . $this->count + 1;

        $options = [
            'shop_id' => $this->shop_id
        ];

        $mysql = new ExecuteMySql($sql, $options);
        $rows = $mysql->execute();

        $this->prev_right = null;
        $this->next_left = isset($rows[$this->count]) ? $rows[$this->count]['id'] : null;

        $rows = array_slice($rows, 0, $this->count);

        return $rows;
    }

    public function fetchNextCustomerList($left): array
    {
        $sql = "SELECT * FROM (
                SELECT * FROM customers WHERE id < {$left} AND shop_id = :shop_id1 ORDER BY id DESC LIMIT 1
            ) x
            UNION ALL
            SELECT * FROM (
                SELECT * FROM customers WHERE id >= {$left} AND shop_id = :shop_id2 ORDER BY id ASC LIMIT " . $this->count + 1
            . ") x";

        $options = [
            'shop_id1' => $this->shop_id,
            'shop_id2' => $this->shop_id
        ];

        $mysql = new ExecuteMySql($sql, $options);
        $rows = $mysql->execute();

        $this->prev_right = isset($rows[0]) && $rows[0]['id'] < $left ? (int) $rows[0]['id'] : null;
        $rows = array_slice($rows, (int) ($this->prev_right !== null));

        $this->next_left = isset($rows[$this->count]) ? $rows[$this->count]['id'] : null;
        $rows = array_slice($rows, 0, $this->count);

        return $rows;
    }

    public function fetchPrevCustomerList($right): array
    {
        $sql = "SELECT * FROM (
                SELECT * FROM customers WHERE id > {$right} AND shop_id = :shop_id1 ORDER BY id ASC LIMIT 1
            ) x
            UNION ALL
            SELECT * FROM (
                SELECT * FROM customers WHERE id <= {$right} AND shop_id = :shop_id2 ORDER BY id DESC LIMIT " . $this->count + 1
            . ") x";

        $options = [
            'shop_id1' => $this->shop_id,
            'shop_id2' => $this->shop_id
        ];

        $mysql = new ExecuteMySql($sql, $options);
        $rows = $mysql->execute();
        // var_dump($rows);
        // exit;

        $this->next_left = isset($rows[0]) && $rows[0]['id'] > $right ? $rows[0]['id'] : null;
        $rows = array_slice($rows, (int) ($this->next_left !== null));

        $this->prev_right = isset($rows[$this->count]) ? (int) $rows[$this->count]['id'] : null;

        $rows = array_slice($rows, 0, $this->count);
        $rows = array_reverse($rows);

        return $rows;
    }

    public function fetchVisitHistoriesData($yyyymm): array
    {
        $validation = new Validation();

        $data = ['yyyymm' => $yyyymm];

        $rules = ['yyyymm' => 'required|yyyymm'];

        $validation->validate($data, $rules);

        //今月〜過去１２ヶ月の範囲かどうか
        $check_date = new DateTime($yyyymm . '-01');
        $start_date = new DateTime('first day of -11 month 00:00');
        $end_date = new DateTime('first day of this month 00:00');

        if ($check_date < $start_date || $end_date < $check_date) {
            throw new Exception('日付の範囲が不正', 500);
        }

        if (empty($validation->getErrors())) {
            $sql = "SELECT v.`date`, c.`id`, CONCAT(c.`last_name`, '　', c.`first_name`) as `name`, v.`memo`
                    FROM `visit_histories` v
                    INNER JOIN `customers` c
                    ON v.`customer_id` = c.`id`
                    WHERE v.`shop_id` = :shop_id
                    AND DATE_FORMAT(`date`, '%Y-%m') = :date
                    ORDER BY v.`date` DESC
                    ";

            $options = [
                'shop_id' => $this->shop_id,
                'date' => $yyyymm
            ];

            $mysql = new ExecuteMySql($sql, $options);

            return $mysql->execute();
        } else {
            throw new Exception('日付の値が不正', 500);
        }
    }
}
