<?php
declare(strict_types=1);

require_once(__DIR__ . '/./Connection.php');


final class ExecuteMySql extends Connection {

    private readonly array $result;

    final public function __construct(private string $query, private array|null $options = null)
    {
        parent::__construct();

        if ($this->options) $this->prepareExecute();

        if (!$this->options) $this->queryExecute();
    }

    final public function execute() {
        return $this->result;
    }

    // private methods

    private function queryExecute() {
        $stmt = $this->pdo->query($this->query);

        $this->result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function prepareExecute() {
        $stmt = $this->pdo->prepare($this->query);

        foreach($this->options as $option_key => &$option_value) {
            $stmt->bindParam(":$option_key", $option_value, $this->chackType($option_value));
        }

        $stmt->execute();

        $this->result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function chackType($option_value) {
        if (gettype($option_value) === "NULL") return PDO::PARAM_NULL;
        if (gettype($option_value) === "integer") return PDO::PARAM_INT;
        if (gettype($option_value) === "string")  return PDO::PARAM_STR;
        if (gettype($option_value) === "boolean") return PDO::PARAM_BOOL;
    }
}
