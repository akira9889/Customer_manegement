<?php
require_once __DIR__ . '/../lib/ExecuteMySql.php';
require_once __DIR__ . '/ValidationInterface.php';
require_once __DIR__ . '/../functions.php';

final class RegisterShop
{
    private readonly int $prefecture_id;

    private readonly string $area;

    private readonly int $company_id;

    private Validation $validation;

    public function __construct(array $shop_data)
    {
        foreach ($shop_data as $key => $value) {
            $this->{$key} = $value;
        }

        $this->validation = new Validation();
    }

    public function register_shop() {

        $this->validate();

        if (empty($this->getErrors())) {
            $sql = "INSERT INTO shops (company_id, prefecture_id, area)
                    VALUES(:company_id, :prefecture_id, :area)";

            $options = [
                'company_id' => $this->company_id,
                'prefecture_id' => $this->prefecture_id,
                'area' => $this->area
            ];

            $mysql = new ExecuteMySql($sql, $options);

            $mysql->execute();

            redirect('/shop_list.php?company_id=' . $this->company_id);
        }
    }

    private function validate()
    {
        $data = [
            'prefecture_id' =>  $this->prefecture_id,
            'area' => $this->area
        ];

        $rules = [
            'prefecture_id' => 'prefecture',
            'area' => 'required|maxlength:10',
        ];

        $this->validation->validate($data, $rules);

        $sql = "SELECT *
                FROM shops
                WHERE company_id = :company_id
                AND `prefecture_id` = :prefecture_id
                AND `area` = :area
                LIMIT 1";

        $options = [
            'company_id' => $this->company_id,
            'prefecture_id' => $this->prefecture_id,
            'area' => $this->area
        ];

        $mysql = new ExecuteMySql($sql, $options);

        $shop = $mysql->execute()[0]?? null;

        if ($shop) {
            $this->validation->addErros('shop', 'この店舗はすでに登録されています。');
        }
    }

    public function getErrors()
    {
        return $this->validation->getErrors();
    }
}
