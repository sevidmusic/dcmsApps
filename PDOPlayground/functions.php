<?php
class devPdoCrud extends PDO
{
    public function runQuery($sql, $params = NULL)
    {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}

class devUser
{
    private $userName;
    private $userId;
    private $userMeta;

    public function getUserName()
    {
        return $this->userName;
    }

    public function getUserId()
    {
        return $this->userId;
    }

}
