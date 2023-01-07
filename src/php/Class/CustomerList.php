<?php
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

    public function fetchCustomerList()
    {
        $sql = "SELECT *
                FROM customers
                WHERE shop_id = :shop_id
                ORDER BY id ASC
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

    public function fetchNextCustomerList($left)
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

    public function fetchPrevCustomerList($right)
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
}
