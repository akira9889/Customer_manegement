<?php

require_once __DIR__ . '/../lib/ExecuteMySql.php';

final class RegisterShop
{
    private readonly int $prefecture_id;

    private readonly string $area;

    public array $err;

    public function __construct(int $prefecture_id, string $area)
    {
        $this->prefecture_id = $prefecture_id;
        $this->area = $area;
    }

    private function fetchCompanyId() {
        return (int) $_GET['company_id'];
    }

    public function register_shop() {
        // $this->validateInputs();

        // if (empty($this->err)) {

            $sql = "INSERT INTO shops (company_id, prefecture_id, area)
                    VALUES(:company_id, :prefecture_id, :area)";

            $options = [
                'company_id' => (int) $_GET['company_id'],
                'prefecture_id' => (int) $this->prefecture_id,
                'area' => $this->area
            ];

            $mysql = new ExecuteMySql($sql, $options);

            $mysql->execute();
        // }
        // return FALSE;
    }
}
